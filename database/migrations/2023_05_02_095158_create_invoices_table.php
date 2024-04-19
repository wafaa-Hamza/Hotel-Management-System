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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->bigInteger('guest_id')->unsigned();
            $table->bigInteger('window_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('invoice_number');
            $table->double('total_room_charge');
            $table->double('total_fb_charge');
            $table->double('total_other_charge');
            $table->double('total_payment');
            $table->double('total_taxes');
            $table->timestamps();
            $table->foreign('guest_id')->references('id')->on('guests')->cascadeOnDelete();
             $table->foreign('window_id')->references('id')->on('windows');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
