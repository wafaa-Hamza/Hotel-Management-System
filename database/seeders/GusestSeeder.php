<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\Guests;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Database\Factories\GuestsFactory;
use Database\Factories\ReservisionFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GusestSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    * @return void
    */
   public function run()
   {
      $roomID = Room::where('rm_name_en','directBill')->select('id')->first()->id;
      Guests::create([
         'folio_no' => 'directBill',
         'in_date' =>"2023-01-01 00:00:00",
         'out_date' =>"2023-01-01 00:00:00",
         'original_out_date' => "2023-01-01 00:00:00",
         'no_of_nights' =>1,
         'rm_rate' => 0,
         //'taxes' => fake()->randomFloat(1, 0.05, 0.025),
         'no_of_pax' =>0,
         //'hotel_note' => fake()->text(),
         //'client_note' =>fake()->text(),
         'way_of_payment' =>'directBill',
         'profile_id' =>2,
         'company_id' =>1,
        // 'room_id' =>(in_array(fake()->randomElement($roomIDs),$roomsNotcheckedIn)) ? fake()->randomElement($roomIDs) : null, 
         'room_id' => $roomID, 
         'room_type_id' =>2,
         //'rate_code_id' => fake()->numberBetween(1,RateCode::count()),
         'market_segment_id' => 1,
         'source_of_business_id' => 1,
         //'meal_id' => fake()->numberBetween(1,Meal::count()),
         'created_by' => 1,
         //'created_inhousGuest_at' =>fake()->date(),
         //'checked_out' =>false,
         //'checkout_by' => fake()->numberBetween(14,User::count()),
         //'checkout_at' => fake()->dateTimeBetween("01-04-2023","20-04-2023"),
         'is_reservation' =>false,
         'res_status' => 'directBill',
         'is_group' => 0,
         'group_code' => null,
         'is_dummy' =>true,
         'Is_PM' =>0,
         'is_cancel' => 0,
         'is_checked_in' => true,
         //'res_no' => fake()->randomDigit(1,49),
         'is_online' => fake()->boolean(),
         //'ref_no' => fake()->randomDigit(1,49),
         //'join_to' =>fake()->randomElement($roomIDs),
         'vat_no' =>'directBill',
         //'company_name' =>fake()->name(),
         //'inv_address' =>fake()->name(),
         'customer_type' =>11,
         'purpose_of_visit' =>10,
         'is_sent_shomoos' =>false,

      ]);
      
      $chickedIn = GuestsFactory::new()->count(300)->create();
      $reservision = ReservisionFactory::new()->count(200)->create();
      Setting::where('id', 1)->update([
         'hotel_date' => '2023-03-20'
      ]);
      
      // $user = UserFactory::new()->create();
      // $request =[];
      // $a = new GuestsFactory($request);
      //$chickedIn = Guests::factory()->count(300)->create();

      // 2..update in table room fo_status='OC' if(table Guest if is_checedIn = true &&is_reservision = false && checked_out=false)
//      Room::whereHas('guests', function ($q) {
//         $q->where('is_checked_in', true)->where('is_reservation', false)->where('checked_out', false);
//      })->update(['fo_status' => 'OC']);
//      //   3..update in table room fo_status='BL' if(table Guest if is_checedIn = false &&is_reservision = true && checked_out=false && in_date = hotelDate) //BL:Blouked
//      $hotelData = Carbon::parse('2023-03-20');
//      Room::whereHas('guests', function ($q) use ($hotelData) {
//         $q->where('is_checked_in', false)->where('is_reservation', true)->where('checked_out', false)->whereDate('in_date', $hotelData);
//      })->update(['fo_status' => 'Bl']);
//      //  4..update in table room fo_status='OO' if(table OOrdSetrvices if is_return =false && start_date <= hotelDate && oord_type = 'OO');
//      Room::whereHas('OordService', function ($q) {
//         $q->where('is_return', false)->where('oord_type', 'OO')->whereDate('start_date', '<=', '2023-03-20');
//      })->update(['fo_status' => 'OO']);
//      //  5..update in table room fo_status='OS' if(table OOrdSetrvices if is_return =false && start_date <= hotelDate && oord_type = 'OS');
//      Room::whereHas('OordService', function ($q) {
//         $q->where('is_return', false)->where('oord_type', 'OS')->whereDate('start_date', '<=', '2023-03-20');
//      })->update(['fo_status' => 'OS']);
//      // 1..update in table guest 50 row room_id = null if(table guest if is_reservision=true && is_checkedIn =false && is_checked_out = false );
//      Guests::where('is_checked_in', false)->where('is_reservation', true)->where('checked_out', false)->where('checked_out', false)->whereBetween('id', [350, 400])
//         ->update(['room_id' => null]);
   }
}
