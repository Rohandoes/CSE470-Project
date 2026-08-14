<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class WeeklyGroceryController extends Controller
{
    public function recommend(Request $request)
    {
        $validated = $request->validate([
            'weekly_budget' => 'required|numeric|min:7',
        ]);

        $weeklyBudget = $validated['weekly_budget'];
        $dailyBudget = $weeklyBudget / 7;
        $mealBudget = $dailyBudget / 3; // breakfast, lunch, dinner

        $allFoods = Food::whereNotNull('price_per_100g')
            ->get()
            ->map(function ($food) {
                $food->value_score = $food->protein_g / max($food->price_per_100g, 1);
                return $food;
            })
            ->values();

        $slots = ['breakfast', 'lunch', 'dinner'];
        $days = [];
        $weekTotal = 0;
        $usedThisWeek = []; // food_id => times used so far this week

        for ($day = 1; $day <= 7; $day++) {
            $dayMeals = [];
            $dayTotal = 0;
            $usedToday = [];

            // Re-rank every day: foods used more already this week drop down the
            // list, so the picks rotate instead of repeating the same top items daily.
            $rankedFoods = $allFoods->sortBy(function ($food) use ($usedThisWeek) {
                $timesUsed = $usedThisWeek[$food->id] ?? 0;
                return [$timesUsed, -$food->value_score];
            })->values();

            foreach ($slots as $slot) {
                $combo = [];
                $mealCost = 0;

                foreach ($rankedFoods as $food) {
                    if (in_array($food->id, $usedToday)) {
                        continue;
                    }
                    if ($mealCost + $food->price_per_100g <= $mealBudget) {
                        $combo[] = [
                            'id' => $food->id,
                            'name' => $food->name,
                            'category' => $food->category,
                            'protein_g' => $food->protein_g,
                            'price_per_100g' => $food->price_per_100g,
                        ];
                        $mealCost += $food->price_per_100g;
                        $usedToday[] = $food->id;
                        $usedThisWeek[$food->id] = ($usedThisWeek[$food->id] ?? 0) + 1;
                    }
                }

                $dayMeals[$slot] = [
                    'items' => $combo,
                    'cost' => round($mealCost, 2),
                ];
                $dayTotal += $mealCost;
            }

            $days[] = [
                'day' => $day,
                'meals' => $dayMeals,
                'day_total' => round($dayTotal, 2),
            ];
            $weekTotal += $dayTotal;
        }

        return response()->json([
            'weekly_budget' => $weeklyBudget,
            'daily_budget' => round($dailyBudget, 2),
            'meal_budget' => round($mealBudget, 2),
            'total_cost' => round($weekTotal, 2),
            'days' => $days,
        ]);
    }
}
