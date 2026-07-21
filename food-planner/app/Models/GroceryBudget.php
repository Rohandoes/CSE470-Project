<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// GroceryBudget.php
class GroceryBudget extends Model {
    protected $fillable = ['user_id','meal_plan_id','week_start_date','total_budget_bdt','estimated_cost_bdt','item_breakdown'];
    protected $casts = ['item_breakdown' => 'array'];
    public function mealPlan(): BelongsTo { return $this->belongsTo(MealPlan::class); }
}
