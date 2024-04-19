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
        Schema::create('pre_charges', function (Blueprint $table) {
            $table->id();
            $table->date('hotel_date');
            $table->date('sys_data');
            $table->bigInteger('guest_id')->unsigned();
            $table->bigInteger('ledger_id')->unsigned();
            $table->double('amount');
            $table->integer('transaction_collection')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->boolean('post_next_day')->nullable()->default(false);
            $table->timestamps();

            $table->foreign('guest_id')->references('id')->on('guests')->cascadeOnDelete();
            $table->foreign('ledger_id')->references('id')->on('ledgers');
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
        Schema::dropIfExists('pre_charges');
    }
};
