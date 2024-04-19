<?php

namespace Database\Seeders;

use App\Models\Ledger;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LeadgerSeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ledgers =  array(
array('code' => '1101', 'name' => 'Auto Room Rate', 'name_loc' => 'ايجار الغرفة آلي', 'type' => 'Dr', 'defult_routing' => '1','ledger_cat_id' => '1', 'scth_ledger_id' => '1'),
array('code' => '1102', 'name' => 'Room Rate Service Charge', 'name_loc' => 'خدمة ايجار الغرفة', 'type' => 'Dr', 'ledger_cat_id' => '1', 'defult_routing' => '1', 'scth_ledger_id' => '1'),
array('code' => '1103', 'name' => 'Room Rate', 'name_loc' => 'ايجار الغرفة', 'type' => 'Dr', 'ledger_cat_id' => '1', 'defult_routing' => '1', 'scth_ledger_id' => '1'),
array('code' => '1104', 'name' => 'ROOM CHARGE MANUAL', 'name_loc' => 'ايجار', 'type' => 'Dr', 'ledger_cat_id' => '1', 'defult_routing' => '1', 'scth_ledger_id' => '1'),
array('code' => '1105', 'name' => 'Rebate Room Rate', 'name_loc' => 'تعديل في ايجار الغرف', 'type' => 'Cr', 'ledger_cat_id' => '1', 'defult_routing' => '1', 'scth_ledger_id' => '1'),
array('code' => '1106', 'name' => 'Discount Room Rate', 'name_loc' => 'خصم ايجار الغرف', 'type' => 'Cr', 'ledger_cat_id' => '1', 'defult_routing' => '1', 'scth_ledger_id' => '1'),
array('code' => '1107', 'name' => 'Room Adjustment Debit +', 'name_loc' => 'تعديل ايجار الغرف +', 'type' => 'Dr', 'ledger_cat_id' => '1', 'defult_routing' => '1', 'scth_ledger_id' => '1'),
array('code' => '1108', 'name' => 'Early Entry', 'name_loc' => 'دخول مبكر', 'type' => 'Dr', 'ledger_cat_id' => '1', 'defult_routing' => '0', 'scth_ledger_id' => '1'),
array('code' => '1109', 'name' => 'Late Check Out', 'name_loc' => 'خروج متأخر', 'type' => 'Dr', 'ledger_cat_id' => '1', 'defult_routing' => '0', 'scth_ledger_id' => '1'),
array('code' => '1110', 'name' => 'Half Night', 'name_loc' => 'نصف ليلة', 'type' => 'Dr', 'ledger_cat_id' => '1', 'defult_routing' => '0', 'scth_ledger_id' => '1'),
array('code' => '2101', 'name' => 'LOCAL PHONE', 'name_loc' => 'مكالمة داخلية', 'type' => 'Dr', 'ledger_cat_id' => '2', 'defult_routing' => '0', 'scth_ledger_id' => '1'),
array('code' => '2102', 'name' => 'FAX', 'name_loc' => 'فاكس', 'type' => 'Dr', 'ledger_cat_id' => '2', 'defult_routing' => '0', 'scth_ledger_id' => '1'),
array('code' => '2103', 'name' => 'INTER NATIONAL', 'name_loc' => 'مكالمة دولية', 'type' => 'Dr', 'ledger_cat_id' => '2', 'defult_routing' => '0', 'scth_ledger_id' => '3'),
array('code' => '2104', 'name' => 'InterNet', 'name_loc' => 'انترنت', 'type' => 'Dr', 'ledger_cat_id' => '2', 'defult_routing' => '0', 'scth_ledger_id' => '3'),
array('code' => '2105', 'name' => 'Rebate Telephony Service', 'name_loc' => 'خصم على خدمة الهاتف', 'type' => 'Cr', 'ledger_cat_id' => '2', 'defult_routing' => '0', 'scth_ledger_id' => '3'),
array('code' => '3101', 'name' => 'Buffet B.Fast', 'name_loc' => 'افطار بوفيه', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '1', 'scth_ledger_id' => '2'),
array('code' => '3102', 'name' => 'Buffet Lunch', 'name_loc' => 'غداء بوفية', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3103', 'name' => 'Buffett Dinner', 'name_loc' => 'عشاء بوفية', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3104', 'name' => 'RENT CHAIR', 'name_loc' => 'ايجار مطعم', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3105', 'name' => 'Iftar Ramadhan', 'name_loc' => 'افطار رمضان', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3106', 'name' => 'Sahour Ramadan', 'name_loc' => 'سحور رمضان', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3107', 'name' => 'Rebate Restaurant', 'name_loc' => 'خصم على المطعم', 'type' => 'Cr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3108', 'name' => 'Breakfast Food', 'name_loc' => 'افطار اغذية', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '1', 'scth_ledger_id' => '2'),
array('code' => '3109', 'name' => 'Breakfast Beverage', 'name_loc' => 'Breakfast Beverage EXTRA', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3110', 'name' => 'Lunch Food', 'name_loc' => 'Lunch Food EXTRA', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3111', 'name' => 'Lunch Beverage', 'name_loc' => 'Lunch Beverage', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3112', 'name' => 'Dinner Food', 'name_loc' => 'Dinner Food EXTRA', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
 array('code' => '3113', 'name' => 'Dinner Beverage', 'name_loc' => 'Dinner Beverage', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3114', 'name' => 'Dinner EXTRA', 'name_loc' => 'عشاء اضافى', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3115', 'name' => 'Lunch EXTRA', 'name_loc' => 'غداء اضافى', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3116', 'name' => 'Breakfast EXTRA', 'name_loc' => 'إفطار اضافى', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3117', 'name' => 'Indo Food', 'name_loc' => 'اندونيسي', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3201', 'name' => 'Coffee Shop', 'name_loc' => 'كوفي شوب', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3202', 'name' => 'Rebate Coffee Shop', 'name_loc' => 'خصم على كوفي شوب', 'type' => 'Cr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3301', 'name' => 'Room Service B.Fast', 'name_loc' => 'خدمة الغرف افطار', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3302', 'name' => 'Room Service Lunch', 'name_loc' => 'خدمة الغرف غداء', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3303', 'name' => 'Room Service Dinner', 'name_loc' => 'خدمة الغرف عشاء', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3304', 'name' => 'Room Service Order', 'name_loc' => 'خدمة الغرف', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3305', 'name' => 'Rebate Room Service', 'name_loc' => 'خصم على خدمة الغرف', 'type' => 'Cr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3306', 'name' => 'Mini bar', 'name_loc' => 'الثلاجة', 'type' => 'Dr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '3307', 'name' => 'Rebate Mini bar', 'name_loc' => 'خصم الثلاجة', 'type' => 'Cr', 'ledger_cat_id' => '3', 'defult_routing' => '0', 'scth_ledger_id' => '2'),
array('code' => '4101', 'name' => 'Laundry Service', 'name_loc' => 'خدمة المغسلة', 'type' => 'Dr', 'ledger_cat_id' => '4', 'defult_routing' => '0', 'scth_ledger_id' => '4'),
array('code' => '4102', 'name' => 'Rebate Laundry Service', 'name_loc' => 'خصم على خدمة المغسلة', 'type' => 'Cr', 'ledger_cat_id' => '4', 'defult_routing' => '0', 'scth_ledger_id' => '4'),
array('code' => '8101', 'name' => 'Car Parking', 'name_loc' => 'موقف السيارات', 'type' => 'Dr', 'ledger_cat_id' => '5', 'defult_routing' => '0', 'scth_ledger_id' => '4'),
array('code' => '8102', 'name' => 'محلات', 'name_loc' => 'SHOPS', 'type' => 'Dr', 'ledger_cat_id' => '5', 'defult_routing' => '0', 'scth_ledger_id' => '4'),
array('code' => '8103', 'name' => 'Damage', 'name_loc' => 'Damage', 'type' => 'Dr', 'ledger_cat_id' => '5', 'defult_routing' => '0', 'scth_ledger_id' => '4'),
array('code' => '8104', 'name' => 'Room layouts and decorations', 'name_loc' => 'تنسيقات وديكورات الغرف', 'type' => 'Dr', 'ledger_cat_id' => '5', 'defult_routing' => '0', 'scth_ledger_id' => '1'),
array('code' => '8201', 'name' => 'Airport To Hotel', 'name_loc' => 'من المطار الى الفندق', 'type' => 'Dr', 'ledger_cat_id' => '5', 'defult_routing' => '0', 'scth_ledger_id' => '4'),
array('code' => '8202', 'name' => 'Taxi', 'name_loc' => 'تاكسي', 'type' => 'Dr', 'ledger_cat_id' => '5', 'defult_routing' => '0', 'scth_ledger_id' => '4'),
array('code' => '8203', 'name' => 'Hotel To Airport', 'name_loc' => 'من الفندق الى المطار', 'type' => 'Dr', 'ledger_cat_id' => '5', 'defult_routing' => '0', 'scth_ledger_id' => '4'),
array('code' => '8204', 'name' => 'Rebate Taxi', 'name_loc' => 'خصم تاكسي', 'type' => 'Cr', 'ledger_cat_id' => '5', 'defult_routing' => '0', 'scth_ledger_id' => '4'),
array('code' => '8401', 'name' => 'Massage', 'name_loc' => 'مساج', 'type' => 'Dr', 'ledger_cat_id' => '5', 'defult_routing' => '0', 'scth_ledger_id' => '4'),
array('code' => '9901', 'name' => 'VAT TAX', 'name_loc' => 'ضريبة القيمة المضافة', 'type' => 'Dr', 'ledger_cat_id' => '6', 'defult_routing' => '1', 'scth_ledger_id' => '4'),
array('code' => '9903', 'name' => 'City Tax', 'name_loc' => 'رسوم البلدية', 'type' => 'Dr', 'ledger_cat_id' => '6', 'defult_routing' => '1', 'scth_ledger_id' => '4')
        );

           DB::table('ledgers')->insert($ledgers);
        
}
}
