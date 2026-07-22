<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// GroceryBudgetController.php
class GroceryBudgetController extends Controller {
    public function __construct(private GroceryBudgetService $service) {}
    public function store(Request $request, MealPlan $mealPlan) {
        $result = $this->service->estimate($mealPlan);
        $budget = GroceryBudget::create([
            'user_id' => $request->user()->id,
            'meal_plan_id' => $mealPlan->id,
            'week_start_date' => $mealPlan->week_start_date,
            'total_budget_bdt' => $mealPlan->budget_bdt ?? $result['total'],
            'estimated_cost_bdt' => $result['total'],
            'item_breakdown' => $result['breakdown'],
        ]);
        return response()->json($budget, 201);
    }
}
