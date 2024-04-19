<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;


use Carbon\Carbon;

use App\Models\RoomType;

use Illuminate\Database\Seeder;
use Database\Seeders\MealSeeder;
use Database\Seeders\RoomChange;
use Database\Seeders\RoomSeeder;

use Database\Seeders\FloorSeeder;
use Database\Seeders\OwnerSeeder;
use Database\Seeders\TowerSeeder;

use Database\Seeders\GusestSeeder;
use Database\Seeders\idTypeSeeder;
use Database\Seeders\MarketSeeder;
use Illuminate\Support\Facades\DB;

use Database\Seeders\CountrySeeder;
use Database\Seeders\RateCodeSeeder;
use Database\Seeders\LeadgerCatSeeder;
use Database\Seeders\RoomStatusSeeder;
use Database\Seeders\ReservationStatusSeeder;
use Database\Factories\RoomTypeFactory;
use Database\Seeders\CheckStatusSeeder;
use Database\Seeders\GuestProfileSeeder;
use Database\Seeders\GroupPayMasterSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {


        $this->call([
            RoleSeeder::class,
            permissionSeeder::class,
            OwnerSeeder::class,
            idTypeSeeder::class,
            TowerSeeder::class,
            FloorSeeder::class,
            GroupPayMasterSeeder::class,
            RoomStatusSeeder::class,
            RoomTypeSeeder::class,
            RoomSeeder::class,
            CountrySeeder::class,
            BusenessSeeder::class,
            LeadgerCatSeeder::class,
            LeadgerSeder::class,
            MealSeeder::class,
            MealBackageSeeder::class,
            RateCodeSeeder::class,
            MarketSeeder::class,
            OordServicesSeeder::class,
            GuestProfileSeeder::class,
            // // GusestSeeder::class,
            ReservationStatusSeeder::class
            // RoomChange::class,

        ]);

        DB::table('settings')->insert([
            'name' => "Acme Inc.",
            'name_loc' => "شركة آكمي المحدودة",
            'type' => "Corporation",
            'cr_no' => "12345",
            'phone' => "555-1234",
            'mobile' => "555-5678",
            'email' => "info@acme.com",
            'city' => "New York",
            'address' => "123 Main St.",
            'vat_no' => "5",
            'hotel_date' => Carbon::now()->format('Y-m-d'),
            'allow_overbooking' => '0',
            'last_rate_posting' => Carbon::now()->format('Y-m-d H:i:s'),
            'direct_print_voucher' => '0',
            'direct_print_invoice' => '0',
            'due_out_close' => '0',
            'def_ledger_id' => '1'
        ]);
        // ReservationStatusSeeder::class,

        //  RoomTypeSeeder::class,
        //  FloorSeeder::class,
        //  CheckStatusSeeder::class

        //  ]);




    }
}
