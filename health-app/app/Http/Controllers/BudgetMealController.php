<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class BudgetMealController extends Controller
{
    public function recommend(Request $request)
    {
        $validated = $request->validate([
            'budget' => 'required|numeric|min:1',
        ]);

        $budget = $validated['budget'];

        $foods = Food::whereNotNull('price_per_100g')
            ->get()
            ->map(function ($food) {
                $food->value_score = $food->protein_g / max($food->price_per_100g, 1);
                return $food;
            })
            ->sortByDesc('value_score');

        $combo = [];
        $totalCost = 0;

        foreach ($foods as $food) {
            if ($totalCost + $food->price_per_100g <= $budget) {
                $combo[] = $food;
                $totalCost += $food->price_per_100g;
            }
        }

        return response()->json([
            'budget' => $budget,
            'total_cost' => $totalCost,
            'recommended_items' => $combo,
        ]);
    }
}