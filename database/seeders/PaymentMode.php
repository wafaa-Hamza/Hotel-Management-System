<?php

namespace Database\Seeders;

use App\Models\PaymentMode as ModelsPaymentMode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMode extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ModelsPaymentMode::create([
            'name' => 'cityledger',
            'name_loc' => 'cityledger'
        ]);
    }
}