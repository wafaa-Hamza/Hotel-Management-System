<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_status_histories', function (Blueprint $table) {
            $table->id();
            $table->dateTime('hotel_date');
            $table->bigInteger('room_id')->unsigned();
            $table->bigInteger('user_id')->unsigned();
            $table->string('description');
            $table->string('fo_status_from');
            $table->string('fo_status_to');
            $table->string('hk_status_from');
            $table->string('hk_status_to');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_status_histories');
    }
};
