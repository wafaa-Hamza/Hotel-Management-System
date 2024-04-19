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
        Schema::create('transaction_taxe_histories', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary();
            $table->bigInteger('transaction_history_id')->unsigned();
            $table->foreignId('tax_id')->constrained();
            $table->double('amount');
            $table->string('name');
            $table->string('name_loc');
            $table->boolean('inc');
            $table->timestamps();
            $table->foreign('transaction_history_id')->references('id')->on('transaction_histories')->onDelete('cascade');
           // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_taxe_histories');
    }
};
