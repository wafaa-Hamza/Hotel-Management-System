<?php
namespace Database\Seeders;
use App\Models\Floor;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
class FloorSeeder extends Seeder
{
    public function run()
    {
        Floor::create([
            'floor_name'         => 'floor1',
            'floor_name_loc'     => 'asd',
            'active'             => 1,
            'no_of_rooms'        => 3,
            'sort_order'         => 1,
        ]);
        Floor::create([
            'floor_name'         => 'floor2',
            'floor_name_loc'     => 'asd',
            'active'             => 1,
            'no_of_rooms'        => 3,
            'sort_order'         => 1,
        ]);
        Floor::create([
            'floor_name'         => 'floor3',
            'floor_name_loc'     => 'asd',
            'active'             => 1,
            'no_of_rooms'        => 3,
            'sort_order'         => 1,
        ]);
    }
}
