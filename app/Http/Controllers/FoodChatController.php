<?php

namespace App\Http\Controllers;

use App\Services\GroqChatAdvisor;
use Illuminate\Http\Request;

class FoodChatController extends Controller
{
    public function ask(Request $request, GroqChatAdvisor $advisor)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:500',
            'history' => 'nullable|array',
            'history.*.role' => 'required_with:history|in:user,assistant',
            'history.*.content' => 'required_with:history|string',
        ]);

        $reply = $advisor->ask($validated['message'], $validated['history'] ?? [], $request->user());

        return response()->json(['reply' => $reply]);
    }
}
