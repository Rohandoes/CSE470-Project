<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('food_logs', function (Blueprint $table) {
            $table->enum('meal_slot', ['breakfast', 'lunch', 'dinner', 'snack'])
                ->nullable()
                ->after('log_date');
        });
    }

    public function down(): void
    {
        Schema::table('food_logs', function (Blueprint $table) {
            $table->dropColumn('meal_slot');
        });
    }
};
