<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodLogItem extends Model
{
    protected $fillable = ['food_log_id', 'food_id', 'quantity_g'];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function foodLog()
    {
        return $this->belongsTo(FoodLog::class);
    }
}
