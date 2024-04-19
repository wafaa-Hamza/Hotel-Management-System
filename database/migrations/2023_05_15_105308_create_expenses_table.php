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

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->nullable();
            //$table->foreignId('ledger_id')->constrained()->cascadeOnDelete();
            $table->bigInteger('exp_ledger_id')->unsigned();
            $table->double('amount');
            $table->date('hotel_date');
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
            $table->string('file')->nullable();
            $table->timestamps();

            $table->foreign('exp_ledger_id')->references('id')->on('expenses_ledgers')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('expenses');
    }
};
