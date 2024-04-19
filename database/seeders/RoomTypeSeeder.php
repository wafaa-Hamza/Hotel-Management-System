<?php

namespace Database\Seeders;

use App\Models\RoomType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        RoomType::create([
            'rm_type_code' => "1",
            'rm_type_name' => "double",
            'rm_type_name_loc' => "ثنائي",
            'def_pax' => 1,
            'def_price' => 3,
            'no_of_rooms' => 5,
            'rm_type_rentable' =>1,
            'sort_order' =>3,
            'scth_type_code' => 2,
            'def_rate_code' =>"2",
        ]);
         RoomType::create([
                'rm_type_code' => 'DUM',
                'rm_type_name' => 'dummy',
                'rm_type_name_loc' => 'غرفه وهميه',
                'def_pax' => 0,
                'def_price' => 0,
                'no_of_rooms' => 0,
                'rm_type_rentable' => 0,
                'sort_order' => 1,
                'scth_type_code' => 0,
                'def_rate_code' => null,
                'monthly_rate' => 0,
                'yearly_rate' => 0,
                 ]);
                 
        RoomType::create([
            'rm_type_code' => "3",
            'rm_type_name' => "triple",
            'rm_type_name_loc' => "ثلاثي",
            'def_pax' => 1,
            'def_price' => 3,
            'no_of_rooms' => 5,
            'rm_type_rentable' =>1,
            'sort_order' =>3,
            'scth_type_code' => 2,
            'def_rate_code' =>"2",
        ]);

    }
}
