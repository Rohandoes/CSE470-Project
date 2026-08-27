<?php

namespace App\Services;

use App\Models\Food;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqChatAdvisor
{
    protected string $apiKey;
    protected string $model = 'openai/gpt-oss-120b';

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
    }

    public function ask(string $question, array $history, ?User $user): string
    {
        $foods = Food::select('name', 'category', 'calories_per_100g', 'protein_g', 'price_per_100g')
            ->get()
            ->map(fn ($f) => "{$f->name} ({$f->category}, {$f->calories_per_100g} kcal/100g, {$f->protein_g}g protein, ৳{$f->price_per_100g}/100g)")
            ->implode("\n");

        $profileLine = 'No profile info provided yet.';
        $recentLogsLine = 'No meal history yet.';

        if ($user) {
            $bits = ["Name: {$user->name}"];
            if ($user->age) $bits[] = "Age: {$user->age}";
            if ($user->gender) $bits[] = "Gender: {$user->gender}";
            if ($user->height_cm) $bits[] = "Height: {$user->height_cm}cm";
            if ($user->weight_kg) $bits[] = "Weight: {$user->weight_kg}kg";
            if ($bmi = $user->bmi()) $bits[] = "BMI: {$bmi}";
            if ($user->activity_level) $bits[] = "Activity level: {$user->activity_level}";
            if ($user->goal) $bits[] = "Goal: {$user->goal}";
            $profileLine = implode(', ', $bits);

            $recentLogs = $user->foodLogs()->with('items.food')->orderByDesc('log_date')->limit(5)->get();
            if ($recentLogs->isNotEmpty()) {
                $recentLogsLine = $recentLogs->map(function ($log) {
                    $items = $log->items->map(fn ($i) => "{$i->food->name} ({$i->quantity_g}g)")->implode(', ');
                    return "{$log->log_date} [{$log->meal_slot}]: {$items}";
                })->implode("\n");
            }
        }

        $systemPrompt = <<<PROMPT
You are a friendly nutrition assistant inside "Better Life Everyday", a health app built by
Zobairul and his group mates as a CSE470 course project under faculty of BRAC University,CSE Department, Pollock Nag. If asked about the app's name,
who made it, or what it's for, answer with these facts directly — do not call the app "Vitality"
or invent other names. Give practical, encouraging food and meal suggestions, personalized using
the user's profile and recent meal history below. Keep replies short — 2 to 5 sentences,
occasionally a short bullet list. Never give medical diagnoses; if asked something clearly
medical, gently suggest they see a doctor and stick to general food advice. Reference their name
and stats naturally when relevant, don't just recite the data back at them.

User profile:
{$profileLine}

Recent meals logged (most recent first):
{$recentLogsLine}

Foods available in the app's database, prefer recommending these since the user can log them directly:
{$foods}
PROMPT;

        $messages = [['role' => 'system', 'content' => $systemPrompt]];
        foreach ($history as $turn) {
            $messages[] = ['role' => $turn['role'], 'content' => $turn['content']];
        }
        $messages[] = ['role' => 'user', 'content' => $question];

        $response = Http::withToken($this->apiKey)
            ->timeout(30)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $this->model,
                'messages' => $messages,
                'temperature' => 0.5,
            ]);

        if ($response->failed()) {
            Log::error('Groq chat failed', ['body' => $response->body()]);
            return "Sorry, I couldn't reach the AI service right now.";
        }

        return $response->json('choices.0.message.content') ?? "I couldn't come up with a reply — try asking differently.";
    }
}
