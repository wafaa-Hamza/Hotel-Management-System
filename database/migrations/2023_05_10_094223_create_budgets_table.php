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
        Schema::create('budgets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ledger_id')->unsigned();
            $table->year('year');
            $table->double('one');
            $table->double('tow');
            $table->double('three');
            $table->double('four');
            $table->double('five');
            $table->double('six');
            $table->double('seven');
            $table->double('eight');
            $table->double('nine');
            $table->double('ten');
            $table->double('eleven');
            $table->double('twelve');
            $table->timestamps();

            $table->foreign('ledger_id')->references('id')->on('ledgers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budgets');
    }
};
