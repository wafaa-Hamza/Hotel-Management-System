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
        Schema::create('chart_of_account_levels', function (Blueprint $table) {
            $table->id();
            $table->integer('level_length');
            $table->string('level_name',50);
            $table->string('level_name_loc',50);
            $table->string('level_color',50)->nullable();
            $table->integer('ser_gap')->nullable();
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
        Schema::dropIfExists('chart_of_account_levels');
    }
};