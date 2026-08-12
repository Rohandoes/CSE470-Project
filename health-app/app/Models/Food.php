<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Food extends Model
{
    protected $table = 'foods';
    protected $fillable = [
        'name','category','calories_per_100g',
        'protein_g','carbs_g','fat_g','fiber_g','common_portion'
    ];

    public function mealPlanItems()
{
    return $this->hasMany(MealPlanItem::class);
}
}
