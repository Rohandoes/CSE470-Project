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
        Schema::create('food_items', function (Blueprint $table) {
    $table->id();
    $table->string('name'); // e.g. Bhat, Ilish Bhaja, Khichuri
    $table->string('name_bn')->nullable(); // Bangla name
    $table->string('category'); // rice, fish, vegetable, lentil, snack
    $table->decimal('calories_per_100g', 6, 2);
    $table->decimal('protein_g', 5, 2);
    $table->decimal('carbs_g', 5, 2);
    $table->decimal('fat_g', 5, 2);
    $table->decimal('avg_price_bdt_per_100g', 6, 2)->nullable(); // for budget features
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_items');
    }
};
