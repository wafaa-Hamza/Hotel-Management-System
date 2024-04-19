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
        Schema::create('res_rate_days', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('guest_id')->unsigned();
            $table->date('day_date');
            $table->float('rm_rate')->nullable();
            $table->bigInteger('rate_id')->unsigned()->nullable();
            $table->bigInteger('meal_id')->unsigned()->nullable();
            $table->bigInteger('meal_package_id')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('guest_id')->references('id')->on('guests')->cascadeOnDelete();
            $table->foreign('rate_id')->references('id')->on('rate_codes');
            $table->foreign('meal_id')->references('id')->on('meals');
            $table->foreign('meal_package_id')->references('id')->on('meal_packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('res_rate_days');
    }
};
