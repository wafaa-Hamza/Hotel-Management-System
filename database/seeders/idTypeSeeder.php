<?php

namespace Database\Seeders;

use App\Models\IdType;
use Database\Factories\IdTypeFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class idTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //$chickedIn = IdTypeFactory::new()->count(12)->create();
        IdType::create([
            'id_type_code' =>'1',
            'name' => 'Saudi id',
            'name_loc' => 'بطاقه احوال',
        ]);
        IdType::create([
            'id_type_code' =>'2',
            'name' => 'Iqama',
            'name_loc' => 'هويه مقيم',
        ]);
        IdType::create([
            'id_type_code' =>'3',
            'name' => 'Golf id',
            'name_loc' => 'هويه خليجيه',
        ]);
        IdType::create([
            'id_type_code' =>'4',
            'name' => 'Passport',
            'name_loc' => 'جواز سفر',
        ]);
    }
}
