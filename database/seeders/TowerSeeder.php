<?php

namespace Database\Seeders;

use App\Models\Tower;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TowerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Tower::create([
            'name'       => 'tower1',
            'name_loc'             => 'برج1',
        ]);
    }
}
