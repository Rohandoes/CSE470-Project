<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FoodLog extends Model
{
    protected $fillable = ['user_id', 'log_date', 'raw_text'];

    public function items()
    {
        return $this->hasMany(FoodLogItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
