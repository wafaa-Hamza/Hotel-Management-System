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
        Schema::create('room_change_histories', function (Blueprint $table) {         
            $table->id();
            $table->bigInteger('guest_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('from_room_number')->unsigned();
            $table->bigInteger('to_room_number')->unsigned();
            $table->text('reason');
            $table->dateTime('hotel_date')->nullable();
            $table->timestamps();
            $table->foreign('from_room_number')->references('id')->on('rooms');
            $table->foreign('to_room_number')->references('id')->on('rooms');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('guest_id')->references('id')->on('guests')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('room_change_histories');
    }
};