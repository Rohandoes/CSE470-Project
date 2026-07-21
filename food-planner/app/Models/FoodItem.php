<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class FoodItem extends Model {
    protected $fillable = ['name','name_bn','category','calories_per_100g','protein_g','carbs_g','fat_g','avg_price_bdt_per_100g'];
    public function mealPlanItems(): HasMany { return $this->hasMany(MealPlanItem::class); }
}