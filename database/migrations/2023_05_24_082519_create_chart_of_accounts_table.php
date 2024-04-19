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
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->integer('account_code');
            $table->bigInteger('main_account_id')->unsigned()->nullable();
            $table->string('account_name',50);
            $table->string('account_name_loc',50);
            $table->bigInteger('account_level')->unsigned();
            $table->string('account_class',10);
            $table->integer('account_type');
            $table->boolean('is_transaction');
            $table->bigInteger('created_by')->unsigned();
            $table->timestamps();

            $table->foreign('main_account_id')->references('id')->on('chart_of_accounts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chart_of_accounts');
    }
};