<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Meal::create([
            'meal_code' => 'BF',
            'name' => 'Breakfast',
            'name_loc' => 'افطار',
            'price' => 30,
            'ledger_id' => 1,
            'meal_type' => 'BF',
        ]);


        Meal::create([
            'meal_code' => 'LN',
            'name' => 'Lunch',
            'name_loc' => 'غداء',
            'price' => 30,
            'ledger_id' => 1,
            'meal_type' => 'Ln',
        ]);

        Meal::create([
            'meal_code' => 'DI',
            'name' => 'Dinner',
            'name_loc' => 'عشاء',
            'price' => 30,
            'ledger_id' => 1,
            'meal_type' => 'Di',
        ]);


        Meal::create([
            'meal_code' => 'IF',
            'name' => 'Iftar Ramadan',
            'name_loc' => 'افطار رمضان',
            'price' => 30,
            'ledger_id' => 1,
            'meal_type' => 'IF',
        ]);

        Meal::create([
            'meal_code' => 'SR',
            'name' => 'Sahour',
            'name_loc' => 'سحور',
            'price' => 30,
            'ledger_id' => 1,
            'meal_type' => 'Sr'
        ]);



        Meal::create([
            'meal_code' => 'OT',
            'name' => 'Other meal',
            'name_loc' => 'وجبة اخرى',
            'price' => 30,
            'ledger_id' => 1,
            'meal_type' => 'Ot',
        ]);
    }
}
