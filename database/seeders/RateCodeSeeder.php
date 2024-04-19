<?php

namespace Database\Seeders;

use App\Models\RateCode;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RateCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RateCode::create([
            'rate_code' => 'as',
            'name' => 'asd',
            'name_loc' => 'asd',
            'start_date' => '2023/02/01',
            'end_date' =>'2023/02/01',
            'active' => 1,
            'meal_id' => 2,
            'meal_package_id' => 1,
            'ledger_id' => 1,
            'user_id' => 1,
        ]);
    }
}
