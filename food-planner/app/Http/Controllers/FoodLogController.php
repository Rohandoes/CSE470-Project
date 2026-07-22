<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// FoodLogController.php
class FoodLogController extends Controller {
    public function __construct(private AIFoodParserService $ai) {}
    public function store(Request $request) {
        $data = $request->validate(['raw_input' => 'required|string']);
        $parsed = $this->ai->parse($data['raw_input']);

        $log = FoodLog::create([
            'user_id' => $request->user()->id,
            'food_item_id' => $parsed['food_item_id'],
            'raw_input' => $data['raw_input'],
            'quantity_g' => $parsed['quantity_g'],
            'parsed_calories' => $parsed['calories'],
            'logged_at' => now(),
        ]);
        return response()->json($log, 201);
    }
}
