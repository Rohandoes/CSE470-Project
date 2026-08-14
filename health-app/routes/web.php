<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/dashboard', function () {
    return view('dashboard.home');
});
Route::get('/dashboard/foods', function () {
    return view('dashboard.foods');
});
Route::get('/dashboard/meal-planner', function () {
    return view('dashboard.meal-planner');
});
Route::get('/dashboard/budget-meal', function () {
    return view('dashboard.budget-meal');
});
Route::get('/dashboard/weekly-grocery', function () {
    return view('dashboard.weekly-grocery');
});
Route::get('/dashboard/login', function () {
    return view('dashboard.login');
});
Route::get('/dashboard/food-logger', function () {
    return view('dashboard.food-logger');
});

require __DIR__.'/auth.php';
