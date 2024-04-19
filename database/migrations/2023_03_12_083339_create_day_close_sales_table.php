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
        Schema::create('day_close_sales', function (Blueprint $table) {
            $table->id();
            $table->datetime('hotel_date');
            $table->datetime('sys_date');
            $table->bigInteger('ledger_id')->unsigned();
            $table->bigInteger('payment_type_id')->unsigned();
            $table->double('charge_amount');
            $table->double('Payment_amount');
            $table->softDeletes();
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
        Schema::dropIfExists('day_close_sales');
    }
};
