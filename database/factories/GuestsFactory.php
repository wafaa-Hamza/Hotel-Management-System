<?php

namespace Database\Factories;

use App\Models\companyProfile;
use App\Models\guest_profile;
use App\Models\Guests;
use App\Models\Market;
use App\Models\Meal;
use App\Models\RateCode;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\SourceBusiness;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\=Guest>
 */
class GuestsFactory extends Factory
{
    protected $model = Guests::class;
 //factory for chicked in
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $roomIDs=[];
        $roomChickedInIDs=[];
        //$roomChickedInIDs=[];
        $roomsId=Room::where('fo_status','VA')
        ->get('id');
        $roomscheckedIn=Guests::where('is_checked_in',1)
        ->get('room_id');
        if($roomscheckedIn)
        {
            foreach($roomscheckedIn as $roomID)
            {
                array_push($roomChickedInIDs,$roomID->id);
            }     
        }
        
        foreach($roomsId as $roomID)
        {
            if($roomChickedInIDs)
            {
                     if(!in_array($roomID,$roomChickedInIDs))
                    {
                        array_push($roomIDs,$roomID->id);
                    }               
            }else{
                array_push($roomIDs,$roomID->id);
            }
        }
             
        $data = [
            'folio_no' => fake()->unique()->randomNumber(),
             'in_date' =>fake()->dateTimeBetween("01-02-2023","30-03-2023"),
             'out_date' =>fake()->dateTimeBetween("01-04-2023","20-04-2023"),
             'original_out_date' => fake()->dateTimeBetween("15-04-2023","30-04-2023"),
             'no_of_nights' =>fake()->numberBetween(5,30),
             'rm_rate' => fake()->numberBetween(1000,5000),
             'taxes' => fake()->randomFloat(1, 0.05, 0.025),
             'no_of_pax' =>fake()->numberBetween(1,5),
             'hotel_note' => fake()->text(),
             'client_note' =>fake()->text(),
             'way_of_payment' =>fake()->name(),
             'profile_id' =>fake()->numberBetween(1,guest_profile::count()),
             'company_id' =>fake()->numberBetween(1,companyProfile::count()),
            // 'room_id' =>(in_array(fake()->randomElement($roomIDs),$roomsNotcheckedIn)) ? fake()->randomElement($roomIDs) : null, 
             'room_id' => fake()->unique()->randomElement($roomIDs), 
             'room_type_id' =>fake()->numberBetween(1,RoomType::count()),
             'rate_code_id' => fake()->numberBetween(1,RateCode::count()),
             'market_segment_id' =>fake()->numberBetween(1,Market::count()),
             'source_of_business_id' => fake()->numberBetween(1,SourceBusiness::count()),
             'meal_id' => fake()->numberBetween(1,Meal::count()),
             'created_by' => fake()->numberBetween(1,User::count()),
             'created_inhousGuest_at' =>fake()->date(),
             'checked_out' =>false,
             'checkout_by' => fake()->numberBetween(14,User::count()),
             'checkout_at' => fake()->dateTimeBetween("01-04-2023","20-04-2023"),
             'is_reservation' =>false,
             'res_status' => fake()->randomElement(['CNF','NCF','CNC','NSH']),
             'is_group' => fake()->boolean(),
             'group_code' => null,
             'is_dummy' =>fake()->boolean(),
             'Is_PM' =>fake()->boolean(),
             'is_cancel' => fake()->boolean(),
             'is_checked_in' => true,
             'res_no' => fake()->randomDigit(1,49),
             'is_online' => fake()->boolean(),
             'ref_no' => fake()->randomDigit(1,49),
             'join_to' =>fake()->randomElement($roomIDs),
             'vat_no' =>fake()->name(),
             'company_name' =>fake()->name(),
             'inv_address' =>fake()->name(),
             'customer_type' =>fake()->randomDigit(1,49),
             'purpose_of_visit' =>fake()->randomDigit(1,49),
             'is_sent_shomoos' =>fake()->boolean(),

        ];

        return $data;
    }
}
