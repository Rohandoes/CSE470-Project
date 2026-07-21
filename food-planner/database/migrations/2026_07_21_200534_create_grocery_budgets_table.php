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
        Schema::create('grocery_budgets', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('meal_plan_id')->nullable()->constrained();
    $table->date('week_start_date');
    $table->decimal('total_budget_bdt', 8, 2);
    $table->decimal('estimated_cost_bdt', 8, 2)->nullable();
    $table->json('item_breakdown')->nullable(); // food_item_id => cost
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grocery_budgets');
    }
};
