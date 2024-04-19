<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChartOfAccountsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        $chartOfAccounts = array(
            array('account_code' => 1, 'account_name' => 'Assets' ,'account_name_loc' => 'اصول ثابته', 'account_level' => 1,'account_type' => 1, 'account_class' => 1,'created_by' => 1,'is_transaction' => false),
            array('account_code' => 2, 'account_name' => 'Liabilities' ,'account_name_loc' => 'الالتزامات', 'account_level' => 1,'account_type' => 2, 'account_class' => 2,'created_by' => 1,'is_transaction' => false),
            array('account_code' => 3, 'account_name' => 'equity' ,'account_name_loc' => ' رأس المال', 'account_level' => 1,'account_type' => 3, 'account_class' => 3,'created_by' => 1,'is_transaction' => false),
            array('account_code' => 4, 'account_name' => 'revenues' ,'account_name_loc' => 'الايرادات', 'account_level' => 1,'account_type' => 4, 'account_class' => 4,'created_by' => 1,'is_transaction' => false),
            array('account_code' => 5, 'account_name' => 'expenses' ,'account_name_loc' => 'المصروفات', 'account_level' => 1,'account_type' => 5, 'account_class' => 5,'created_by' => 1,'is_transaction' => false),
           
        );
        DB::table('chart_of_accounts')->insert($chartOfAccounts);
    }
}