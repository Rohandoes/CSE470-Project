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
    Schema::create('foods', function (Blueprint $table) {
        $table->id();
        $table->string('name');              // e.g. "Bhuna Khichuri"
        $table->string('category');          // rice, curry, snack, drink, etc
        $table->float('calories_per_100g');
        $table->float('protein_g');
        $table->float('carbs_g');
        $table->float('fat_g');
        $table->float('fiber_g')->nullable();
        $table->string('common_portion')->nullable(); // "1 bowl ~250g"
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
