<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\IdType>
 */
class IdTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_type_code' =>fake()->numberBetween(101,299),
            'name' =>fake()->name() ,
            'name_loc' => fake()->name(),
        ];
    }
}
