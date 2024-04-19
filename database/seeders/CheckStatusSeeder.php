<?php

namespace Database\Seeders;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\CheckStatus;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class CheckStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CheckStatus::factory()->count(500)->create();
      
    }
}
?>