<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// FoodLog.php
class FoodLog extends Model {
    protected $fillable = ['user_id','food_item_id','raw_input','quantity_g','parsed_calories','logged_at'];
    protected $casts = ['logged_at' => 'datetime'];
    public function foodItem(): BelongsTo { return $this->belongsTo(FoodItem::class); }
}