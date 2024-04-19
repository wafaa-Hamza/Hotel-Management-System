<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CheckStatus>
 */
class CheckStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition():array
    {
        return ([

             Room::create([ 
                     'rm_name_en'                => fake()->numberBetween(100,999),
                     'rm_name_loc'               => fake()->numberBetween(100,999),
                     'rm_max_pax'                => fake()->numberBetween(1,10),
                     'rm_phone_no'               => '1234',
                     'rm_phone_ext'              => '123456',
                     'rm_key_code'               => '1236',
                     'rm_key_options'            => 1,
                     'rm_connection'             => 1,
                     'fo_status'                 => fake()->randomElement(['VA','OO','OC','OO']),
                     'rm_active'                 => 1,
                     'hk_stauts'                 => fake()->randomElement(['CL','DI']),
                     'sort_order'                => 1,
                     'room_type_id'              => fake()->numberBetween(1,3),
                     'floor_id'                  => fake()->numberBetween(1,3)

             ])
            ]);
            
            
        
    }
}

?>