<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoodSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $foods = [
            // Rice & Grains
            ['name' => 'Plain Rice (Bhat)', 'category' => 'rice', 'calories_per_100g' => 130, 'protein_g' => 2.7, 'carbs_g' => 28, 'fat_g' => 0.3, 'fiber_g' => 0.4, 'common_portion' => '1 cup ~150g', 'price_per_100g' => 6],
            ['name' => 'Brown Rice', 'category' => 'rice', 'calories_per_100g' => 123, 'protein_g' => 2.6, 'carbs_g' => 26, 'fat_g' => 1.0, 'fiber_g' => 1.8, 'common_portion' => '1 cup ~150g', 'price_per_100g' => 9],
            ['name' => 'Khichuri', 'category' => 'rice', 'calories_per_100g' => 150, 'protein_g' => 4.5, 'carbs_g' => 25, 'fat_g' => 3.5, 'fiber_g' => 1.5, 'common_portion' => '1 bowl ~250g', 'price_per_100g' => 10],
            ['name' => 'Chirer Polao (Flattened Rice)', 'category' => 'rice', 'calories_per_100g' => 356, 'protein_g' => 6.6, 'carbs_g' => 76, 'fat_g' => 1.2, 'fiber_g' => 1.0, 'common_portion' => '1 cup ~80g', 'price_per_100g' => 8],
            ['name' => 'Roti (Chapati)', 'category' => 'grain', 'calories_per_100g' => 264, 'protein_g' => 8.7, 'carbs_g' => 50, 'fat_g' => 3.7, 'fiber_g' => 4.5, 'common_portion' => '1 piece ~40g', 'price_per_100g' => 12],
            ['name' => 'Paratha', 'category' => 'grain', 'calories_per_100g' => 330, 'protein_g' => 6.5, 'carbs_g' => 45, 'fat_g' => 14, 'fiber_g' => 2.5, 'common_portion' => '1 piece ~60g', 'price_per_100g' => 15],
            ['name' => 'Oats', 'category' => 'grain', 'calories_per_100g' => 389, 'protein_g' => 16.9, 'carbs_g' => 66, 'fat_g' => 6.9, 'fiber_g' => 10.6, 'common_portion' => '1/2 cup dry ~40g', 'price_per_100g' => 18],

            // Dal & Lentils
            ['name' => 'Masoor Dal', 'category' => 'dal', 'calories_per_100g' => 116, 'protein_g' => 9, 'carbs_g' => 20, 'fat_g' => 0.4, 'fiber_g' => 8, 'common_portion' => '1 bowl ~200g', 'price_per_100g' => 12],
            ['name' => 'Moong Dal', 'category' => 'dal', 'calories_per_100g' => 105, 'protein_g' => 7.5, 'carbs_g' => 19, 'fat_g' => 0.4, 'fiber_g' => 7.6, 'common_portion' => '1 bowl ~200g', 'price_per_100g' => 14],
            ['name' => 'Chana Dal', 'category' => 'dal', 'calories_per_100g' => 164, 'protein_g' => 10.7, 'carbs_g' => 27, 'fat_g' => 2.6, 'fiber_g' => 8, 'common_portion' => '1 bowl ~200g', 'price_per_100g' => 13],
            ['name' => 'Kabuli Chana (Chickpeas)', 'category' => 'dal', 'calories_per_100g' => 164, 'protein_g' => 8.9, 'carbs_g' => 27, 'fat_g' => 2.6, 'fiber_g' => 7.6, 'common_portion' => '1 bowl ~200g', 'price_per_100g' => 16],
            ['name' => 'Anda Dal (Boiled Lentil Mix)', 'category' => 'dal', 'calories_per_100g' => 120, 'protein_g' => 8.5, 'carbs_g' => 21, 'fat_g' => 0.5, 'fiber_g' => 7, 'common_portion' => '1 bowl ~200g', 'price_per_100g' => 13],

            // Fish
            ['name' => 'Ilish Bhaja (Fried Hilsa)', 'category' => 'fish', 'calories_per_100g' => 257, 'protein_g' => 20, 'carbs_g' => 0, 'fat_g' => 19, 'fiber_g' => 0, 'common_portion' => '1 piece ~100g', 'price_per_100g' => 45],
            ['name' => 'Rui Maach (Rohu Curry)', 'category' => 'fish', 'calories_per_100g' => 150, 'protein_g' => 22, 'carbs_g' => 1, 'fat_g' => 6.5, 'fiber_g' => 0, 'common_portion' => '1 piece ~100g', 'price_per_100g' => 28],
            ['name' => 'Katla Maach', 'category' => 'fish', 'calories_per_100g' => 111, 'protein_g' => 19, 'carbs_g' => 0, 'fat_g' => 3.5, 'fiber_g' => 0, 'common_portion' => '1 piece ~100g', 'price_per_100g' => 26],
            ['name' => 'Pangash Maach', 'category' => 'fish', 'calories_per_100g' => 130, 'protein_g' => 18, 'carbs_g' => 0, 'fat_g' => 6, 'fiber_g' => 0, 'common_portion' => '1 piece ~100g', 'price_per_100g' => 18],
            ['name' => 'Shing Maach (Catfish)', 'category' => 'fish', 'calories_per_100g' => 100, 'protein_g' => 17, 'carbs_g' => 0, 'fat_g' => 3, 'fiber_g' => 0, 'common_portion' => '1 piece ~80g', 'price_per_100g' => 35],
            ['name' => 'Chingri Maach (Prawn)', 'category' => 'fish', 'calories_per_100g' => 99, 'protein_g' => 24, 'carbs_g' => 0.2, 'fat_g' => 0.3, 'fiber_g' => 0, 'common_portion' => '5-6 pieces ~100g', 'price_per_100g' => 60],
            ['name' => 'Mola Maach (Small Fish)', 'category' => 'fish', 'calories_per_100g' => 120, 'protein_g' => 18, 'carbs_g' => 0, 'fat_g' => 5, 'fiber_g' => 0, 'common_portion' => '1 handful ~50g', 'price_per_100g' => 30],

            // Meat & Poultry
            ['name' => 'Murgir Mangsho (Chicken Curry)', 'category' => 'meat', 'calories_per_100g' => 190, 'protein_g' => 25, 'carbs_g' => 2, 'fat_g' => 9, 'fiber_g' => 0.5, 'common_portion' => '1 piece ~100g', 'price_per_100g' => 30],
            ['name' => 'Grilled Chicken Breast', 'category' => 'meat', 'calories_per_100g' => 165, 'protein_g' => 31, 'carbs_g' => 0, 'fat_g' => 3.6, 'fiber_g' => 0, 'common_portion' => '1 piece ~120g', 'price_per_100g' => 32],
            ['name' => 'Gorur Mangsho (Beef Curry)', 'category' => 'meat', 'calories_per_100g' => 250, 'protein_g' => 26, 'carbs_g' => 2, 'fat_g' => 15, 'fiber_g' => 0, 'common_portion' => '1 piece ~100g', 'price_per_100g' => 55],
            ['name' => 'Khashir Mangsho (Mutton Curry)', 'category' => 'meat', 'calories_per_100g' => 294, 'protein_g' => 25, 'carbs_g' => 2, 'fat_g' => 21, 'fiber_g' => 0, 'common_portion' => '1 piece ~100g', 'price_per_100g' => 65],
            ['name' => 'Boiled Egg (Dim)', 'category' => 'meat', 'calories_per_100g' => 155, 'protein_g' => 13, 'carbs_g' => 1.1, 'fat_g' => 11, 'fiber_g' => 0, 'common_portion' => '1 egg ~50g', 'price_per_100g' => 14],
            ['name' => 'Egg Omelette', 'category' => 'meat', 'calories_per_100g' => 190, 'protein_g' => 13.5, 'carbs_g' => 1.5, 'fat_g' => 14.5, 'fiber_g' => 0, 'common_portion' => '1 omelette ~80g', 'price_per_100g' => 15],

            // Vegetables
            ['name' => 'Aloo Bhaji (Fried Potato)', 'category' => 'vegetable', 'calories_per_100g' => 130, 'protein_g' => 2, 'carbs_g' => 20, 'fat_g' => 5, 'fiber_g' => 2.2, 'common_portion' => '1 bowl ~150g', 'price_per_100g' => 8],
            ['name' => 'Begun Bhaji (Fried Eggplant)', 'category' => 'vegetable', 'calories_per_100g' => 90, 'protein_g' => 1.2, 'carbs_g' => 8, 'fat_g' => 6, 'fiber_g' => 3, 'common_portion' => '1 bowl ~150g', 'price_per_100g' => 7],
            ['name' => 'Lau (Bottle Gourd Curry)', 'category' => 'vegetable', 'calories_per_100g' => 40, 'protein_g' => 1, 'carbs_g' => 8, 'fat_g' => 0.5, 'fiber_g' => 2, 'common_portion' => '1 bowl ~150g', 'price_per_100g' => 6],
            ['name' => 'Shobji Bhaji (Mixed Vegetable)', 'category' => 'vegetable', 'calories_per_100g' => 85, 'protein_g' => 2.5, 'carbs_g' => 12, 'fat_g' => 3, 'fiber_g' => 3.5, 'common_portion' => '1 bowl ~150g', 'price_per_100g' => 9],
            ['name' => 'Palong Shak (Spinach)', 'category' => 'vegetable', 'calories_per_100g' => 23, 'protein_g' => 2.9, 'carbs_g' => 3.6, 'fat_g' => 0.4, 'fiber_g' => 2.2, 'common_portion' => '1 bowl ~100g', 'price_per_100g' => 8],
            ['name' => 'Lal Shak (Red Amaranth)', 'category' => 'vegetable', 'calories_per_100g' => 28, 'protein_g' => 3.4, 'carbs_g' => 4, 'fat_g' => 0.5, 'fiber_g' => 2.8, 'common_portion' => '1 bowl ~100g', 'price_per_100g' => 8],
            ['name' => 'Kumra (Pumpkin Curry)', 'category' => 'vegetable', 'calories_per_100g' => 45, 'protein_g' => 1.5, 'carbs_g' => 9, 'fat_g' => 0.8, 'fiber_g' => 1.8, 'common_portion' => '1 bowl ~150g', 'price_per_100g' => 6],
            ['name' => 'Karola Bhaji (Bitter Gourd)', 'category' => 'vegetable', 'calories_per_100g' => 55, 'protein_g' => 2, 'carbs_g' => 7, 'fat_g' => 3, 'fiber_g' => 2.9, 'common_portion' => '1 bowl ~120g', 'price_per_100g' => 9],
            ['name' => 'Dhonepata Bhorta (Coriander Mash)', 'category' => 'vegetable', 'calories_per_100g' => 60, 'protein_g' => 1.8, 'carbs_g' => 6, 'fat_g' => 3.5, 'fiber_g' => 2, 'common_portion' => '2 tbsp ~30g', 'price_per_100g' => 10],
            ['name' => 'Aloo Bhorta (Mashed Potato)', 'category' => 'vegetable', 'calories_per_100g' => 110, 'protein_g' => 1.8, 'carbs_g' => 18, 'fat_g' => 3.5, 'fiber_g' => 2, 'common_portion' => '1 bowl ~120g', 'price_per_100g' => 7],
            ['name' => 'Salad (Cucumber Tomato)', 'category' => 'vegetable', 'calories_per_100g' => 20, 'protein_g' => 1, 'carbs_g' => 4, 'fat_g' => 0.2, 'fiber_g' => 1.2, 'common_portion' => '1 bowl ~100g', 'price_per_100g' => 5],

            // Fruits
            ['name' => 'Banana (Kola)', 'category' => 'fruit', 'calories_per_100g' => 89, 'protein_g' => 1.1, 'carbs_g' => 23, 'fat_g' => 0.3, 'fiber_g' => 2.6, 'common_portion' => '1 medium ~100g', 'price_per_100g' => 10],
            ['name' => 'Mango (Aam)', 'category' => 'fruit', 'calories_per_100g' => 60, 'protein_g' => 0.8, 'carbs_g' => 15, 'fat_g' => 0.4, 'fiber_g' => 1.6, 'common_portion' => '1 cup sliced ~150g', 'price_per_100g' => 12],
            ['name' => 'Papaya (Pepe)', 'category' => 'fruit', 'calories_per_100g' => 43, 'protein_g' => 0.5, 'carbs_g' => 11, 'fat_g' => 0.3, 'fiber_g' => 1.7, 'common_portion' => '1 cup ~150g', 'price_per_100g' => 7],
            ['name' => 'Guava (Peyara)', 'category' => 'fruit', 'calories_per_100g' => 68, 'protein_g' => 2.6, 'carbs_g' => 14, 'fat_g' => 1, 'fiber_g' => 5.4, 'common_portion' => '1 medium ~100g', 'price_per_100g' => 9],
            ['name' => 'Jackfruit (Kathal)', 'category' => 'fruit', 'calories_per_100g' => 95, 'protein_g' => 1.7, 'carbs_g' => 23, 'fat_g' => 0.6, 'fiber_g' => 1.5, 'common_portion' => '1 cup ~150g', 'price_per_100g' => 11],
            ['name' => 'Watermelon (Tarmuj)', 'category' => 'fruit', 'calories_per_100g' => 30, 'protein_g' => 0.6, 'carbs_g' => 8, 'fat_g' => 0.2, 'fiber_g' => 0.4, 'common_portion' => '1 cup ~150g', 'price_per_100g' => 6],
            ['name' => 'Orange (Komola)', 'category' => 'fruit', 'calories_per_100g' => 47, 'protein_g' => 0.9, 'carbs_g' => 12, 'fat_g' => 0.1, 'fiber_g' => 2.4, 'common_portion' => '1 medium ~130g', 'price_per_100g' => 14],
            ['name' => 'Apple (Apel)', 'category' => 'fruit', 'calories_per_100g' => 52, 'protein_g' => 0.3, 'carbs_g' => 14, 'fat_g' => 0.2, 'fiber_g' => 2.4, 'common_portion' => '1 medium ~150g', 'price_per_100g' => 20],
            ['name' => 'Pomegranate (Dalim)', 'category' => 'fruit', 'calories_per_100g' => 83, 'protein_g' => 1.7, 'carbs_g' => 19, 'fat_g' => 1.2, 'fiber_g' => 4, 'common_portion' => '1/2 cup seeds ~80g', 'price_per_100g' => 25],

            // Dairy
            ['name' => 'Doi (Sweet Yogurt)', 'category' => 'dairy', 'calories_per_100g' => 150, 'protein_g' => 5, 'carbs_g' => 22, 'fat_g' => 4.5, 'fiber_g' => 0, 'common_portion' => '1 bowl ~100g', 'price_per_100g' => 15],
            ['name' => 'Plain Yogurt', 'category' => 'dairy', 'calories_per_100g' => 61, 'protein_g' => 3.5, 'carbs_g' => 4.7, 'fat_g' => 3.3, 'fiber_g' => 0, 'common_portion' => '1 bowl ~100g', 'price_per_100g' => 12],
            ['name' => 'Milk (Dudh)', 'category' => 'dairy', 'calories_per_100g' => 61, 'protein_g' => 3.2, 'carbs_g' => 4.8, 'fat_g' => 3.3, 'fiber_g' => 0, 'common_portion' => '1 glass ~200ml', 'price_per_100g' => 8],
            ['name' => 'Panir (Cottage Cheese)', 'category' => 'dairy', 'calories_per_100g' => 265, 'protein_g' => 18, 'carbs_g' => 3.4, 'fat_g' => 20, 'fiber_g' => 0, 'common_portion' => '100g cube', 'price_per_100g' => 35],

            // Snacks & Misc
            ['name' => 'Chola Bhuna (Spiced Chickpeas)', 'category' => 'snack', 'calories_per_100g' => 180, 'protein_g' => 8, 'carbs_g' => 25, 'fat_g' => 6, 'fiber_g' => 7, 'common_portion' => '1 bowl ~150g', 'price_per_100g' => 14],
            ['name' => 'Muri (Puffed Rice)', 'category' => 'snack', 'calories_per_100g' => 402, 'protein_g' => 7.5, 'carbs_g' => 80, 'fat_g' => 3.5, 'fiber_g' => 2.2, 'common_portion' => '1 cup ~30g', 'price_per_100g' => 9],
            ['name' => 'Singara (Samosa)', 'category' => 'snack', 'calories_per_100g' => 260, 'protein_g' => 4.5, 'carbs_g' => 28, 'fat_g' => 15, 'fiber_g' => 2, 'common_portion' => '1 piece ~50g', 'price_per_100g' => 20],
            ['name' => 'Piyaju (Lentil Fritters)', 'category' => 'snack', 'calories_per_100g' => 280, 'protein_g' => 9, 'carbs_g' => 24, 'fat_g' => 17, 'fiber_g' => 5, 'common_portion' => '2 pieces ~40g', 'price_per_100g' => 18],
            ['name' => 'Peanuts (Chinabadam)', 'category' => 'snack', 'calories_per_100g' => 567, 'protein_g' => 25.8, 'carbs_g' => 16, 'fat_g' => 49, 'fiber_g' => 8.5, 'common_portion' => '1 handful ~30g', 'price_per_100g' => 22],
            ['name' => 'Almonds (Kaath Badam)', 'category' => 'snack', 'calories_per_100g' => 579, 'protein_g' => 21, 'carbs_g' => 22, 'fat_g' => 50, 'fiber_g' => 12.5, 'common_portion' => '1 handful ~30g', 'price_per_100g' => 90],
            ['name' => 'Boondi (Sweet)', 'category' => 'snack', 'calories_per_100g' => 400, 'protein_g' => 6, 'carbs_g' => 55, 'fat_g' => 18, 'fiber_g' => 1, 'common_portion' => '1 small bowl ~50g', 'price_per_100g' => 25],
        ];

        $rows = array_map(function ($food) use ($now) {
            return array_merge($food, [
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }, $foods);

        DB::table('foods')->insert($rows);
    }
}
