<?php

namespace Database\Seeders;

use App\Models\Guests;
use App\Models\Window;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class windowSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $guestID = Guests::where('folio_no','directBill')->select('id')->first()->id;
        Window::create([
            'guest_id' => $guestID,
            'window_number' => 1,
            'window_name' =>'directBill',
            'invoice_number' => '1',
        ]);
        Window::create([
            'guest_id' => 1,
            'window_number' => 1,
            'window_name' =>'windw1',
            'invoice_number' => '1',
        ]);
    }
}
