<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// BudgetMealController.php
class BudgetMealController extends Controller {
    public function __construct(private BudgetMealService $service) {}
    public function recommend(Request $request) {
        $data = $request->validate(['target_calories'=>'required|numeric','budget_bdt'=>'required|numeric']);
        return response()->json($this->service->recommend($data['target_calories'], $data['budget_bdt']));
    }
}
