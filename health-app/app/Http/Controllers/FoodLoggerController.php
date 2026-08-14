<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Illuminate\Http\Request;

class FoodLoggerController extends Controller
{
    protected array $numberWords = [
        'a' => 1, 'an' => 1, 'one' => 1, 'two' => 2, 'three' => 3,
        'four' => 4, 'five' => 5, 'six' => 6, 'seven' => 7,
        'eight' => 8, 'nine' => 9, 'ten' => 10, 'couple' => 2, 'few' => 3,
    ];

    public function store(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string|max:500',
            'log_date' => 'required|date',
        ]);

        $foods = Food::all();
        [$matches, $unmatched] = $this->parseText($validated['text'], $foods);

        $log = $request->user()->foodLogs()->create([
            'log_date' => $validated['log_date'],
            'raw_text' => $validated['text'],
        ]);

        foreach ($matches as $m) {
            $log->items()->create([
                'food_id' => $m['food_id'],
                'quantity_g' => $m['quantity_g'],
            ]);
        }

        return response()->json([
            'log' => $log->load('items.food'),
            'unmatched' => $unmatched,
        ]);
    }

    public function index(Request $request)
    {
        return $request->user()->foodLogs()->with('items.food')->orderByDesc('log_date')->get();
    }

    protected function parseText(string $text, $foods): array
    {
        $segments = preg_split('/\s*(?:,|\band\b|\bwith\b)\s*/i', $text);
        $matches = [];
        $unmatched = [];

        foreach ($segments as $seg) {
            $seg = trim($seg);
            if ($seg === '') {
                continue;
            }

            // Strip trailing "for lunch/breakfast/dinner/snack"
            $seg = preg_replace('/\bfor\s+(breakfast|lunch|dinner|snack)\b/i', '', $seg);
            $seg = trim($seg, " .\t\n\r\0\x0B");

            // Also strip a leading "I had" / "I ate" type phrase if present
            $seg = preg_replace('/^\s*i\s+(had|ate|took|drank)\s+/i', '', $seg);
            $seg = trim($seg);

            // Extract quantity: digit first, then number words
            $qty = 1;
            if (preg_match('/^(\d+)\s*(.*)$/', $seg, $m)) {
                $qty = (int) $m[1];
                $seg = trim($m[2]);
            } else {
                foreach ($this->numberWords as $word => $val) {
                    if (preg_match('/^' . preg_quote($word, '/') . '\b\s*(.*)$/i', $seg, $m)) {
                        $qty = $val;
                        $seg = trim($m[1]);
                        break;
                    }
                }
            }

            // Strip common serving-unit phrases like "bowls of", "cups of"
            $seg = preg_replace('/\b(bowls?|cups?|pieces?|plates?|glass(?:es)?|servings?|slices?)\s+of\s+/i', '', $seg);
            $seg = trim($seg);

            if ($seg === '') {
                continue;
            }

            $best = $this->findBestFoodMatch($seg, $foods);

            if ($best) {
                $gramsPerServing = 100;
                // Only match the approximate portion size (e.g. "~200ml" or "~150g"),
                // never a bare digit that happens to sit next to an unrelated "g"
                // (like the "1" in "1 glass" matching the g in "glass").
                if (preg_match('/~\s*(\d+)\s*(?:g|ml)\b/i', $best->common_portion ?? '', $gm)) {
                    $gramsPerServing = (int) $gm[1];
                }
                $matches[] = [
                    'food_id' => $best->id,
                    'name' => $best->name,
                    'quantity_g' => $qty * $gramsPerServing,
                    'matched_phrase' => $seg,
                ];
            } else {
                $unmatched[] = $seg;
            }
        }

        return [$matches, $unmatched];
    }

    protected function findBestFoodMatch(string $seg, $foods)
    {
        $segLower = strtolower($seg);

        foreach ($foods as $food) {
            $nameLower = strtolower(preg_replace('/\(.*?\)/', '', $food->name));
            $nameLower = trim($nameLower);
            if ($nameLower !== '' && (str_contains($nameLower, $segLower) || str_contains($segLower, $nameLower))) {
                return $food;
            }
        }

        // Fallback: word-by-word overlap, in case exact substring didn't hit
        $segWords = preg_split('/\s+/', $segLower);
        $bestScore = 0;
        $bestFood = null;
        foreach ($foods as $food) {
            $nameLower = strtolower(preg_replace('/\(.*?\)/', '', $food->name));
            $nameWords = preg_split('/\s+/', trim($nameLower));
            $score = count(array_intersect($segWords, $nameWords));
            if ($score > $bestScore) {
                $bestScore = $score;
                $bestFood = $food;
            }
        }

        return $bestScore > 0 ? $bestFood : null;
    }
}