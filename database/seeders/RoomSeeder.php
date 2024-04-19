<?php

namespace Database\Seeders;

use App\Models\Floor;
use App\Models\Room;
use App\Models\RoomStatus;
use App\Models\RoomType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $payMasterID = RoomType::where('rm_type_name','PayMaster')->first()->id;
        $floor_id = Floor::first()->id;
        $roomStatusID = RoomStatus::first()->id;
        Room::create([
            'rm_name_en'       => 'directBill',
            'rm_name_loc'     => 'directBill',
            'rm_max_pax' => 0,
            'rm_phone_no'      => '0',
            'rm_phone_ext'      => '0',
            'rm_key_code'      => '0',
            'rm_key_options'      => 1,
            'rm_connection'      => 1,
            'fo_status'      => 'VA',
            'rm_active'      => 1,
            'hk_stauts'      => 'CL',
            'sort_order'      => 1,
            'room_type_id'      => $payMasterID,
            'floor_id'      => $floor_id,
            'room_statuse_id' =>1,

        ]);
        // Room::create([
        //     'rm_name_en'       => '105',
        //     'rm_name_loc'     => '105',
        //     'rm_max_pax' => 2,
        //     'rm_phone_no'      => '1234',
        //     'rm_phone_ext'      => '123456',
        //     'rm_key_code'      => '1236',
        //     'rm_key_options'      => 1,
        //     'rm_connection'      => 1,
        //     'fo_status'      => 'VA',
        //     'rm_active'      => 1,
        //     'hk_stauts'      => 'CL',
        //     'sort_order'      => 1,
        //     'room_type_id'      => 6,
        //     'floor_id'      => 1,
        //     'room_statuse_id' =>6,
        // ]);
        //Room::factory()->count(500)->create();
    }
}
