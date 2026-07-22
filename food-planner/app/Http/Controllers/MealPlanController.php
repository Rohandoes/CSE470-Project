<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// MealPlanController.php
class MealPlanController extends Controller {
    public function store(Request $request) {
        $data = $request->validate([
            'week_start_date' => 'required|date',
            'target_calories' => 'nullable|numeric',
            'budget_bdt' => 'nullable|numeric',
        ]);
        $plan = $request->user()->mealPlans()->create($data);
        return response()->json($plan, 201);
    }

    public function addItem(Request $request, MealPlan $mealPlan) {
        $data = $request->validate([
            'food_item_id' => 'required|exists:food_items,id',
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'day_of_week' => 'required|in:mon,tue,wed,thu,fri,sat,sun',
            'quantity_g' => 'required|numeric|min:1',
        ]);
        $item = $mealPlan->items()->create($data);
        return response()->json($item, 201);
    }
}
