<?php

namespace Database\Seeders;

use App\Models\DetailsIntegration_Table;
use App\Models\Integration;
use App\Models\MasterIntegrationTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShomoosHeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
       $master = MasterIntegrationTable::create([
            'name' => 'shomoos',
            'name_loc' => 'shomoos',
            'start_date' => '2024-01-20',
            'end_date' => '2024-12-20',
           // 'is_active' => '1',
        ]);
        DetailsIntegration_Table::create([
            'master_id' => $master->id,
            'key' =>'RequestId',
            'value' => '123654',
        ]);
        DetailsIntegration_Table::create([
            'master_id' => $master->id,
            'key' =>'UserId',
            'value' => '2315331914',
        ]);
        DetailsIntegration_Table::create([
            'master_id' => $master->id,
            'key' =>'BranchCode',
            'value' => '3-5850053835-288263',
        ]);
        DetailsIntegration_Table::create([
            'master_id' => $master->id,
            'key' =>'BranchSecret',
            'value' => 'qv2wBD-7odhTFNH9_tPKCA2',
        ]);
        DetailsIntegration_Table::create([
            'master_id' => $master->id,
            'key' =>'Lang',
            'value' => 'ar',
        ]);
    
    }
}
