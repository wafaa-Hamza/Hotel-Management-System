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
        Schema::create('ratechanges', function (Blueprint $table) {
            $table->id();
            $table->datetime('hotel_date');
            $table->bigInteger('guest_id')->unsigned();
            $table->bigInteger('from_rate_code_id')->unsigned()->nullable();
            $table->bigInteger('to_rate_code_id')->unsigned();
            $table->string('from_rm_rate');
            $table->string('to_rm_rate');
            $table->bigInteger('room_id')->unsigned();
            $table->string('raeson');
            $table->bigInteger('created_by')->unsigned();
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
        Schema::dropIfExists('ratechanges');
    }
};
