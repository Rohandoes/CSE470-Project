<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Services\GroqFoodParser;
use Illuminate\Http\Request;

class FoodLoggerController extends Controller
{
    public function store(Request $request, GroqFoodParser $parser)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:500',
        ]);

        $now = now();
        $mealSlot = $this->detectMealSlot($now->hour);

        $result = $parser->parse($validated['text']);

        $log = $request->user()->foodLogs()->create([
            'log_date' => $now->toDateString(),
            'meal_slot' => $mealSlot,
            'raw_text' => $validated['text'],
            'ai_reply' => $result['reply'] ?? null,
        ]);

        $newlyAdded = [];

        foreach ($result['items'] ?? [] as $item) {
            $foodId = $item['food_id'] ?? null;

            if (!$foodId && !empty($item['new_food'])) {
                $nf = $item['new_food'];

                $food = Food::create([
                    'name' => $nf['name'] ?? 'Unknown item',
                    'category' => $nf['category'] ?? 'snack',
                    'calories_per_100g' => $nf['calories_per_100g'] ?? 100,
                    'protein_g' => $nf['protein_g'] ?? 0,
                    'carbs_g' => $nf['carbs_g'] ?? 0,
                    'fat_g' => $nf['fat_g'] ?? 0,
                    'fiber_g' => $nf['fiber_g'] ?? 0,
                    'common_portion' => $nf['common_portion'] ?? '1 serving ~100g',
                    'price_per_100g' => $nf['price_per_100g'] ?? null,
                ]);

                $foodId = $food->id;
                $newlyAdded[] = $food->name;
            }

            if ($foodId) {
                $log->items()->create([
                    'food_id' => $foodId,
                    'quantity_g' => $item['quantity_g'] ?? 100,
                ]);
            }
        }

        return response()->json([
            'log' => $log->load('items.food'),
            'ai_reply' => $result['reply'] ?? '',
            'newly_added_foods' => $newlyAdded,
        ]);
    }

    public function index(Request $request)
    {
        return $request->user()->foodLogs()
            ->with('items.food')
            ->orderByDesc('log_date')
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Decide breakfast/lunch/dinner/snack purely from the current
     * server hour — no manual input from the user at all.
     */
    protected function detectMealSlot(int $hour): string
    {
        return match (true) {
            $hour >= 5 && $hour < 11  => 'breakfast',
            $hour >= 11 && $hour < 16 => 'lunch',
            $hour >= 16 && $hour < 22 => 'dinner',
            default => 'snack',
        };
    }
}
