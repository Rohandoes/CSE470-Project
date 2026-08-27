<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\MealPlan;
use Illuminate\Http\Request;

class MealPlanController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->mealPlans()->with('items.food')->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'plan_date' => 'required|date',
            'meal_slot' => 'required|in:breakfast,lunch,dinner,snack',
            'items' => 'required|array|min:1',
            'items.*.food_id' => 'required|exists:foods,id',
            'items.*.quantity_g' => 'required|numeric|min:1',
        ]);

        $mealPlan = $request->user()->mealPlans()->create([
            'plan_date' => $validated['plan_date'],
            'meal_slot' => $validated['meal_slot'],
        ]);

        foreach ($validated['items'] as $item) {
            $mealPlan->items()->create($item);
        }

        return $mealPlan->load('items.food');
    }

    public function destroy(MealPlan $mealPlan)
    {
        $mealPlan->delete();
        return response()->noContent();
    }

    /**
     * Suggest a realistic, healthy combo for a given meal slot.
     * Picks one food per required food-group category, favoring
     * the best protein-per-calorie option in each group — not
     * just the cheapest, so the result actually looks like a
     * real balanced breakfast/lunch/dinner.
     */
    public function suggest(Request $request)
    {
        $validated = $request->validate([
            'meal_slot' => 'required|in:breakfast,lunch,dinner,snack',
        ]);

        $structure = match ($validated['meal_slot']) {
            'breakfast' => ['grain', 'dairy', 'fruit'],
            'lunch'     => ['rice', 'dal', 'vegetable', rand(0, 1) ? 'fish' : 'meat'],
            'dinner'    => ['grain', 'dal', 'vegetable'],
            'snack'     => ['fruit', 'snack'],
        };

        $picked = [];
        foreach (array_unique($structure) as $category) {
            $food = Food::where('category', $category)
                ->whereNotNull('price_per_100g')
                ->get()
                ->sortByDesc(function ($f) {
                    return $f->calories_per_100g > 0 ? $f->protein_g / $f->calories_per_100g : 0;
                })
                ->first();

            if ($food) {
                $gramsPerServing = 150;
                if (preg_match('/~?\s*(\d+)\s*g/i', $food->common_portion ?? '', $m)) {
                    $gramsPerServing = (int) $m[1];
                }
                $picked[] = [
                    'food_id' => $food->id,
                    'name' => $food->name,
                    'category' => $food->category,
                    'quantity_g' => $gramsPerServing,
                    'calories_per_100g' => $food->calories_per_100g,
                    'protein_g' => $food->protein_g,
                ];
            }
        }

        return response()->json([
            'meal_slot' => $validated['meal_slot'],
            'suggested_items' => $picked,
        ]);
    }
}
