<?php

namespace Database\Seeders;

use App\Models\RoomStatus;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RoomStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $statuses = [
            [
                'status_code' => 'VACL',
                'name' => 'Vacant/Clean',
                'name_loc' => 'شاغرة/نظيفة',
                'color' => '#4CAF50'
            ],
            [
                'status_code' => 'VADI',
                'name' => 'Vacant/Dirty',
                'name_loc' => 'شاغرة/غير نظيفة',
                'color' => '#F44336'
            ],
            [
                'status_code' => 'BLCL',
                'name' => 'Blocked/Clean',
                'name_loc' => 'محجوزة/نظيفة',
                'color' => '#FFEB3B'
            ],
            [
                'status_code' => 'BLDI',
                'name' => 'Blocked/Dirty',
                'name_loc' => 'محجوزة/غير نظيفة',
                'color' => '#3F51B5'
            ],
            [
                'status_code' => 'BLOC',
                'name' => 'Blocked/Occupied',
                'name_loc' => 'محجوزة/ساكنة',
                'color' => '#AB47BC'
            ],
            [
                'status_code' => 'OCCL',
                'name' => 'Occupied/Clean',
                'name_loc' => 'ساكنة/نظيفة',
                'color' => '#D32F2F'
            ],
            [
                'status_code' => 'OCDI',
                'name' => 'Occupied/Dirty',
                'name_loc' => 'ساكنة/غير نظيفة',
                'color' => '#AD1457'
            ],
            [
                'status_code' => 'DO',
                'name' => 'Due Out',
                'name_loc' => 'متوقع مغادرة',
                'color' => '#CDDC39'
            ],
            [
                'status_code' => 'OO',
                'name' => 'Out of Order',
                'name_loc' => 'خارج الطلب',
                'color' => '#81D4FA'
            ],
            [
                'status_code' => 'OS',
                'name' => 'Out Of Service',
                'name_loc' => 'خارج الخدمة',
                'color' => '#FF9800'
            ],
        ];

        foreach ($statuses as $status) {
            DB::table('room_statuses')->insert($status);
        }
    }
}
