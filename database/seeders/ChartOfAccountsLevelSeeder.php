<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChartOfAccountsLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $chartOfAccounts = array(
            array('level_name' => 'mainLevel', 'level_length' => 1 ,'level_name_loc' => 'المستوي الاساسي', 'level_color' => 'blue', 'ser_gap' => 1),
            array('level_name' => 'level1', 'level_length' => 2 ,'level_name_loc' => 'المتوي الاول', 'level_color' => 'red', 'ser_gap' => 2),
            array('level_name' => 'level2', 'level_length' => 2 ,'level_name_loc' => 'المستوي الثاني', 'level_color' => 'green', 'ser_gap' => 3),
            array('level_name' => 'level3', 'level_length' => 3 ,'level_name_loc' => 'الميتوي الثالث', 'level_color' => 'green', 'ser_gap' => 4),
            array('level_name' => 'level4', 'level_length' => 3 ,'level_name_loc' => 'المستوي الرابع', 'level_color' => 'green', 'ser_gap' => 5),
        );
        DB::table('chart_of_account_levels')->insert($chartOfAccounts);
    }
}