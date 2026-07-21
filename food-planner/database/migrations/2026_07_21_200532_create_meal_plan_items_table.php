<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('meal_plan_items', function (Blueprint $table) {
    $table->id();
    $table->foreignId('meal_plan_id')->constrained()->cascadeOnDelete();
    $table->foreignId('food_item_id')->constrained();
    $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack']);
    $table->enum('day_of_week', ['mon','tue','wed','thu','fri','sat','sun']);
    $table->decimal('quantity_g', 6, 2)->default(100);
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meal_plan_items');
    }
};
