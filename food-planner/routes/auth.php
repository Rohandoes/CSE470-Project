<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/foods', [FoodDatabaseController::class, 'index'])->name('foods.index');

    Route::post('/meal-plans', [MealPlanController::class, 'store'])->name('meal-plans.store');
    Route::post('/meal-plans/{mealPlan}/items', [MealPlanController::class, 'addItem'])->name('meal-plans.items.store');

    Route::post('/budget-meals/recommend', [BudgetMealController::class, 'recommend'])->name('budget-meals.recommend');

    Route::post('/meal-plans/{mealPlan}/grocery-budget', [GroceryBudgetController::class, 'store'])->name('grocery-budget.store');

    Route::post('/food-logs', [FoodLogController::class, 'store'])->name('food-logs.store');
});