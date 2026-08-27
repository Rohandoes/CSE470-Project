<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealPlan extends Model
{
    protected $fillable = ['user_id','plan_date','meal_slot'];

    public function items()
    {
        return $this->hasMany(MealPlanItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
