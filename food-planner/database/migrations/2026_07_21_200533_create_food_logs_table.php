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
        Schema::create('food_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->foreignId('food_item_id')->nullable()->constrained();
    $table->text('raw_input'); // "ami dupure 2 plate bhat khaisi"
    $table->decimal('quantity_g', 6, 2)->nullable();
    $table->decimal('parsed_calories', 7, 2)->nullable();
    $table->timestamp('logged_at');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_logs');
    }
};
