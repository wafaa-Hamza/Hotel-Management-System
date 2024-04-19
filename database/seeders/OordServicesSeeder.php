<?php

namespace Database\Seeders;

use App\Models\OordService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OordServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // OordService::factory()->count(500)->create();
        OordService::create(
            [
                'room_id' => 1,
                'oord_type' => 'OO',        //(will be OO, OS)
                'start_date' => "2023-01-04",
                'end_date' => "3023-12-30",
                'notes' => "tenst",
                'created_by' => 1,       //(user_id)
                'is_return' => false,       //(boolean) default 0
                'return_by' => null,      //(user_id)
                'return_date' => null,
            ]
        );
    }
}
