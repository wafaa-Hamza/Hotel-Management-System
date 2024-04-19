<?php

namespace Database\Seeders;

use App\Models\LedgerCat;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LeadgerCatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $ledgerCategories =  array(
             array('name' => 'Accomodation',        'name_loc' => 'الاسكان'),
             array('name' => 'Communication',       'name_loc' => 'الاتصالات'),
             array('name' => 'Food and Beverages',  'name_loc' => 'اغذية و مشروبات'),
             array('name' => 'Laundry',             'name_loc' => 'المغسلة'),
             array('name' => 'Other Revenues',      'name_loc' => 'ايرادات اخرى'),
             array('name' => 'Taxes',               'name_loc' => 'الضرائب'),
            );

            DB::table('ledger_cats')->insert($ledgerCategories);
    }
}
