<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// MealPlan.php
class MealPlan extends Model {
    protected $fillable = ['user_id','week_start_date','target_calories','budget_bdt'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function items(): HasMany { return $this->hasMany(MealPlanItem::class); }
    public function groceryBudget(): HasOne { return $this->hasOne(GroceryBudget::class); }
}
