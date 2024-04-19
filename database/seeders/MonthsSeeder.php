<?php

namespace Database\Seeders;

use App\Models\Month;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MonthsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $months = [
            'January' => 'يناير',
            'February' => 'فبراير',
            'March' => 'مارس',
            'April' => 'ابريل',
            'May' => 'مايو',
            'June' => 'يونيو',
            'July' => 'يوليو',
            'August' => 'اغسطس',
            'September' => 'سبتمبر',
            'October' => 'اكتوبر',
            'November' => 'نوفمبر',
            'December' => 'ديسمبر',
        ];
        foreach($months as $key => $value)
        {
            Month::create([
                'name' => $key,
                'name_loc' => $value,
            ]);
        }
        }
      
}
