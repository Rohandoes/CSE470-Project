<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MealPlanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    return $request->user()->mealPlans()->with('items.food')->get();
}

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MealPlan $mealPlan)
{
    $mealPlan->delete();
    return response()->noContent();
}
}