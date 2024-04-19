<?php

namespace Database\Seeders;

use App\Models\SourceBusiness;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusenessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SourceBusiness::create([
            'name'     => 'Source Business',
            'name_loc' => 'مجال العمل',
        ]);
    }
}
