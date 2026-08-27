<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('food_logs', function (Blueprint $table) {
            $table->text('ai_reply')->nullable()->after('raw_text');
        });
    }

    public function down(): void
    {
        Schema::table('food_logs', function (Blueprint $table) {
            $table->dropColumn('ai_reply');
        });
    }
};
