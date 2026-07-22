<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
{
    DB::table('foods')->insert([
        ['name' => 'Plain Rice (Bhat)', 'category' => 'rice', 'calories_per_100g' => 130, 'protein_g' => 2.7, 'carbs_g' => 28, 'fat_g' => 0.3, 'fiber_g' => 0.4, 'common_portion' => '1 cup ~150g', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Masoor Dal', 'category' => 'dal', 'calories_per_100g' => 116, 'protein_g' => 9, 'carbs_g' => 20, 'fat_g' => 0.4, 'fiber_g' => 8, 'common_portion' => '1 bowl ~200g', 'created_at' => now(), 'updated_at' => now()],
        ['name' => 'Ilish Bhaja (Fried Hilsa)', 'category' => 'fish', 'calories_per_100g' => 257, 'protein_g' => 20, 'carbs_g' => 0, 'fat_g' => 19, 'fiber_g' => 0, 'common_portion' => '1 piece ~100g', 'created_at' => now(), 'updated_at' => now()],
    ]);
}
}


