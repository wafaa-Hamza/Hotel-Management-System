<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoomType>
 */
class RoomTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'rm_type_code' => fake()->countryCode(),
            'rm_type_name' =>fake()->name(),
            'rm_type_name_loc' =>fake()->name(),
            'def_pax' =>fake()->numberBetween(10,1000),
            'def_price' =>fake()->numberBetween(10,1000),
            'no_of_rooms' => fake()->numberBetween(1,50),
            'rm_type_rentable' =>fake()->boolean(),
            'sort_order' => '1',
            'scth_type_code' =>fake()->numberBetween(1,11),
            'def_rate_code' =>fake()->name(),
        ];
    }
}
