<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'age', 'gender', 'height_cm', 'weight_kg', 'activity_level', 'goal'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function mealPlans()
    {
        return $this->hasMany(MealPlan::class);
    }

    public function foodLogs()
    {
        return $this->hasMany(FoodLog::class);
    }

    /**
     * BMI = weight(kg) / height(m)^2. Null if height or weight not set.
     */
    public function bmi(): ?float
    {
        if (!$this->height_cm || !$this->weight_kg) {
            return null;
        }
        $heightM = $this->height_cm / 100;
        return round($this->weight_kg / ($heightM * $heightM), 1);
    }
}
