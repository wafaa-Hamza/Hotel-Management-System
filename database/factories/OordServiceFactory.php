<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OordServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $roomIDs = [];
        $roomsID = Room::where('fo_status','OO')->orWhere('fo_status','OS')->get('id');
        foreach($roomsID as $roomID)
        {
            array_push($roomIDs,$roomID->id);
        }
        return [
            'room_id' => fake()->randomElement($roomIDs),
            'oord_type' => fake()->randomElement(['OO','OS']),        //(will be OO, OS)
            'start_date' => fake()->dateTimeBetween("01-04-2023","20-04-2023"),
            'end_date' =>fake()->dateTimeBetween("01-05-2023","20-05-2023"),
            'notes' => fake()->text(),
            'created_by' => fake()->numberBetween(1,User::count()),       //(user_id)
            'is_return' => fake()->boolean(),       //(boolean) default 0
            'return_by' => fake()->numberBetween(1,User::count()),      //(user_id)
            'return_date' => fake()->dateTimeBetween("01-05-2023","20-05-2023"),
        ];
    }
}
