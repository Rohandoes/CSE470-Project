<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('food_log_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_log_id')->constrained()->onDelete('cascade');
            $table->foreignId('food_id')->constrained('foods')->onDelete('cascade');
            $table->float('quantity_g');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('food_log_items');
    }
};
