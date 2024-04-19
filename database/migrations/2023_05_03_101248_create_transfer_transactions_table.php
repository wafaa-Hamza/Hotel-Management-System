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
        Schema::create('transfer_transactions', function (Blueprint $table) {
            $table->id();
            $table->dateTime('hotel_date');
            $table->dateTime('sys_date');
            $table->bigInteger('trans_id')->unsigned();
            $table->bigInteger('trf_from_guest_id')->unsigned();
            $table->bigInteger('trf_to_guest_id')->unsigned();
            $table->bigInteger('trf_from_window_id')->unsigned();
            $table->bigInteger('trf_to_window_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('reason')->nullable();
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
        Schema::dropIfExists('transfer_transactions');
    }
};
