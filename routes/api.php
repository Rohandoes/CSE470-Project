<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\MealPlanController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\BudgetMealController;
use App\Http\Controllers\WeeklyGroceryController;
use App\Http\Controllers\FoodLoggerController;
use App\Http\Controllers\FoodChatController;
use App\Http\Controllers\ProfileApiController;



Route::post('/login', function (Illuminate\Http\Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    $token = $user->createToken('api-token')->plainTextToken;

    return response()->json(['token' => $token]);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('meal-plans', MealPlanController::class)->only(['index','store','destroy']);
    Route::get('/profile', [ProfileApiController::class, 'show']);
    Route::put('/profile', [ProfileApiController::class, 'update']);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('foods', FoodController::class);
Route::post('/budget-meal/recommend', [BudgetMealController::class, 'recommend']);
Route::post('/weekly-grocery/recommend', [WeeklyGroceryController::class, 'recommend']);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('meal-plans', MealPlanController::class)->only(['index','store','destroy']);
    Route::post('/food-logs', [FoodLoggerController::class, 'store']);
    Route::get('/food-logs', [FoodLoggerController::class, 'index']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('meal-plans', MealPlanController::class)->only(['index','store','destroy']);
    Route::post('/food-logs', [FoodLoggerController::class, 'store']);
    Route::get('/food-logs', [FoodLoggerController::class, 'index']);
});

Route::post('/meal-plans/suggest', [MealPlanController::class, 'suggest']);
Route::post('/food-chat', [FoodChatController::class, 'ask']);
