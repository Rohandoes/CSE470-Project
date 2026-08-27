<?php

namespace App\Services;

use App\Models\Food;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GroqFoodParser
{
    protected string $apiKey;
    protected string $model = 'openai/gpt-oss-120b';

    public function __construct()
    {
        $this->apiKey = config('services.groq.key');
    }

    /**
     * Send the raw text + a list of known foods to Groq, get back
     * a structured breakdown: which existing foods matched, and
     * any brand-new foods the model had to estimate nutrition for.
     */
    public function parse(string $text): array
    {
        $foods = Food::select('id', 'name', 'category')->get();

        $foodList = $foods->map(fn ($f) => "{$f->id}: {$f->name} ({$f->category})")->implode("\n");

        $systemPrompt = <<<PROMPT
You are a food logging assistant for a Bangladeshi health app. The user will describe what they ate in plain language.

Here is the list of foods already in the database (id: name (category)):
{$foodList}

Your job: extract every distinct food item mentioned, with a realistic quantity in grams.

For EACH item, decide:
- If it clearly matches one of the foods above, return "food_id" set to that food's id, and "new_food" set to null.
- If it does NOT match anything above (a genuinely new food), return "food_id" as null, and fill "new_food" with your best realistic nutrition estimate per 100g, using a Bangladeshi food context where relevant.

Respond with ONLY valid JSON, no markdown, no explanation outside the JSON, in this exact shape:
{
  "reply": "A short, friendly one-to-two sentence reply to the user about what you logged.",
  "items": [
    {
      "food_id": 5,
      "new_food": null,
      "quantity_g": 80
    },
    {
      "food_id": null,
      "new_food": {
        "name": "Beef Tehari",
        "category": "rice",
        "calories_per_100g": 220,
        "protein_g": 10,
        "carbs_g": 28,
        "fat_g": 8,
        "fiber_g": 1,
        "common_portion": "1 plate ~250g",
        "price_per_100g": 25
      },
      "quantity_g": 250
    }
  ]
}
PROMPT;

        $response = Http::withToken($this->apiKey)
            ->timeout(30)
            ->post('https://api.groq.com/openai/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $text],
                ],
                'temperature' => 0.2,
                'response_format' => ['type' => 'json_object'],
            ]);

        if ($response->failed()) {
            Log::error('Groq API failed', ['body' => $response->body()]);
            return [
                'reply' => "Sorry, I couldn't reach the AI service right now.",
                'items' => [],
            ];
        }

        $content = $response->json('choices.0.message.content');

        $parsed = json_decode($content, true);

        if (!is_array($parsed) || !isset($parsed['items'])) {
            return [
                'reply' => "I couldn't quite parse that — try rephrasing what you ate.",
                'items' => [],
            ];
        }

        return $parsed;
    }
}
