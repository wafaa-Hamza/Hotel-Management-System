<?php

namespace Database\Seeders;

use App\Models\Room_change_history;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoomChange extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Room_change_history::create([
            'guest_id' => 1,
            'user_id' => 1,
            'from_room_number' =>1,
            'to_room_number' => 1,
            'reason' => 'sadfdsf',
            'hotel_date' => fake()->dateTime(),
        ]);
    }
}
