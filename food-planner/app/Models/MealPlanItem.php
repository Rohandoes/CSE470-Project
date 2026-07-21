<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// MealPlanItem.php
class MealPlanItem extends Model {
    protected $fillable = ['meal_plan_id','food_item_id','meal_type','day_of_week','quantity_g'];
    public function foodItem(): BelongsTo { return $this->belongsTo(FoodItem::class); }
    public function mealPlan(): BelongsTo { return $this->belongsTo(MealPlan::class); }
}