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
        Schema::create('charge_routeds', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('guestID_from')->unsigned();
            $table->bigInteger('ledgerID')->unsigned();
            $table->bigInteger('guestID_to')->unsigned();
            $table->bigInteger('window_id_from')->unsigned();
            $table->bigInteger('window_id_to')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('guestID_from')->references('id')->on('guests')->cascadeOnDelete();
            $table->foreign('guestID_to')->references('id')->on('guests')->cascadeOnDelete();

            $table->foreign('ledgerID')->references('id')->on('ledgers');

            $table->foreign('window_id_from')->references('id')->on('windows');
            $table->foreign('window_id_to')->references('id')->on('windows');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('charge_routeds');
    }
};