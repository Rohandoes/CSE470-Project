<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'age')) {
                $table->unsignedTinyInteger('age')->nullable()->after('email');
            }
            if (!Schema::hasColumn('users', 'gender')) {
                $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('age');
            }
            if (!Schema::hasColumn('users', 'height_cm')) {
                $table->float('height_cm')->nullable()->after('gender');
            }
            if (!Schema::hasColumn('users', 'weight_kg')) {
                $table->float('weight_kg')->nullable()->after('height_cm');
            }
            if (!Schema::hasColumn('users', 'activity_level')) {
                $table->string('activity_level')->nullable()->after('weight_kg');
            }
            if (!Schema::hasColumn('users', 'goal')) {
                $table->string('goal')->nullable()->after('activity_level');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['age', 'gender', 'height_cm', 'weight_kg', 'activity_level', 'goal'];
            $existing = array_filter($columns, fn ($col) => Schema::hasColumn('users', $col));

            if (!empty($existing)) {
                $table->dropColumn($existing);
            }
        });
    }
};