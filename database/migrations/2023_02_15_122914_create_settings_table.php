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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_loc');
            $table->date('hotel_date');
            $table->boolean('allow_overbooking');
            $table->dateTime('last_rate_posting');
            $table->boolean('direct_print_voucher');
            $table->boolean('direct_print_invoice');
            $table->boolean('due_out_close');
            $table->string('type');
            $table->string('cr_no');
            $table->string('phone');
            $table->string('mobile');
            $table->string('email');
            $table->string('city');
            $table->string('address');
            $table->string('vat_no');
            $table->boolean('ntmp_active')->default(0);
            $table->bigInteger('def_ledger_id')->unsigned();
            $table->foreign('def_ledger_id')->references('id')->on('ledgers')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
