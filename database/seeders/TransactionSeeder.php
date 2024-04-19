<?php

namespace Database\Seeders;

use App\Models\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        Transaction::create([

            'guest_id' => 1,
            'res_id' => 1,
            'room_id' => 1,
            'hotel_date' => '2023-05-02',
            'window_id'=>1,
            'ledger_id' => 2,
            'payment_type_id' => 1,
            'charge_amount' => '100',
            'payment_amount' => '50',
            'trans_type' => 'p',
            'recd_type' => 'PAY',
            'ref_id' => 1,
            'description' => 'wow',
            'is_reservation' => 1,
            'created_by' => 1,
            'updated_by' => 1,
            'invoice_id' => 1,
            'inc' => '12',
            'trans_no' => '525',
            'charge_without_taxes' => '54',
            'sys_date' => '2023-05-09',
            'transaction_collection' => '34',
            'voided_at'=>'2023-05-01 09:09:09'
        ]);
        Transaction::factory()->count(30)->create();
    }
}
