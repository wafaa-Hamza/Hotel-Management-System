<?php

namespace Database\Seeders;

use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $taskArr = [
            [
                'name' => 'due_out',
                'name_loc' => 'متوقع المغادره',
                'finish_time' => '15:00',
            ],
            [
                'name' => 'payment_required',
                'name_loc' => 'مطلوب الدفع',
                'finish_time' => '15:00',
            ],
            [
                'name' => 'clean_rooms',
                'name_loc' => 'تنظيف الغرف',
                'finish_time' => '18:00',
            ],
            [
                'name' => 'oo&os_Return',
                'name_loc' => 'اعاده خارج الخدمه و خارج الطلب',
                'finish_time' => '18:00',
            ],
            [
                'name' => 'confirm_reservation',
                'name_loc' => 'تاكيد الحجوزات',
                'finish_time' => '15:00',
            ],
            [
                'name' => 'block_rooms',
                'name_loc' => 'ربط الغرف بالحجزات',
                'finish_time' => '15:00',
            ]
        ];


        foreach($taskArr as $task)
        {
            Task::create($task);

        }
    }
}
