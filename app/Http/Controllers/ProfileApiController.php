<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileApiController extends Controller
{
    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'age' => $user->age,
            'gender' => $user->gender,
            'height_cm' => $user->height_cm,
            'weight_kg' => $user->weight_kg,
            'activity_level' => $user->activity_level,
            'goal' => $user->goal,
            'bmi' => $user->bmi(),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'age' => 'nullable|integer|min:1|max:120',
            'gender' => 'nullable|in:male,female,other',
            'height_cm' => 'nullable|numeric|min:50|max:250',
            'weight_kg' => 'nullable|numeric|min:10|max:300',
            'activity_level' => 'nullable|string|max:50',
            'goal' => 'nullable|string|max:50',
        ]);

        $user = $request->user();
        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated',
            'bmi' => $user->bmi(),
        ]);
    }
}
