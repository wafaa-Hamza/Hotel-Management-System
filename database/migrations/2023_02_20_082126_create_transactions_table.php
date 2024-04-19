<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('transaction_collection')->nullable();
            $table->integer('trans_no');
            $table->foreignId('guest_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('tr_guest_id_from')->unsigned()->nullable();
            $table->integer('res_id')->nullable();
            $table->foreignId('room_id')->nullable()->constrained();
            $table->date('hotel_date');
            $table->date('sys_date');
            $table->foreignId('ledger_id')->nullable()->constrained();
            $table->integer('payment_type_id')->nullable();
            $table->double('charge_amount')->nullable();
            $table->double('charge_without_taxes')->nullable();
            $table->double('payment_amount')->nullable();
            $table->bigInteger('window_id');
            $table->bigInteger('tr_window_id_from')->unsigned()->nullable();
            $table->string('trans_type');
            $table->string('recd_type');
            $table->integer('ref_id')->nullable();
            $table->string('description')->nullable();
            $table->boolean('is_reservation');
            $table->UnsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->boolean('is_void');
            $table->integer('invoice_id')->nullable();
            $table->UnsignedBigInteger('updated_by')->nullable();
            $table->foreign('updated_by')->references('id')->on('users');
            $table->boolean('inc');
            $table->dateTime('voided_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
             $table->foreign('tr_guest_id_from')->references('id')->on('guests');
             $table->foreign('tr_window_id_from')->references('id')->on('windows');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
