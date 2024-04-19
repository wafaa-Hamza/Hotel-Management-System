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
        Schema::create('ratecodes_roomtypes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rate_code_id')->constrained();
            $table->foreignId('room_type_id')->constrained();
            $table->double('pax1');
            $table->double('pax2');
            $table->double('pax3');
            $table->double('pax4');
            $table->double('pax5');
            $table->double('pax6');
            $table->double('extra_pax');
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
        Schema::dropIfExists('ratecodes_roomtypes');
    }
};
