<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// FoodDatabaseController.php
class FoodDatabaseController extends Controller {
    public function __construct(private FoodDatabaseService $service) {}
    public function index(Request $request) {
        return response()->json($this->service->search($request->query('q'), $request->query('category')));
    }
}
