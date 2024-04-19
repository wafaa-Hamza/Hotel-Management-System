<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GroupPayMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        RoomType::create([
            'rm_type_code' => "GPM",
            'rm_type_name' => "PayMaster",
            'rm_type_name_loc' => "غرفه رئيسيه",
            'def_pax' => 0,
            'def_price' => 0,
            'no_of_rooms' => 0,
            'rm_type_rentable' =>false,
            'sort_order' =>0,
            'scth_type_code' => 13,
            'def_rate_code' =>null,
        ]);
    }
}