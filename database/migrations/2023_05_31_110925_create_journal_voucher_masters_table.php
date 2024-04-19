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

        Schema::create('journal_voucher_masters', function (Blueprint $table) {
            $table->id();
            $table->dateTime('jv_date');
            $table->year('fyear');
            $table->integer('fperiod');
            $table->bigInteger('jv_type_id')->unsigned();
            $table->integer('jv_no');
            $table->text('mdescription')->nullable();
            $table->decimal('total_debit');
            $table->decimal('total_credit');
            $table->string('source_code');
            $table->boolean('is_auto_jv');
            $table->boolean('is_posted');
            $table->boolean('is_reverse');
            $table->bigInteger('created_by')->unsigned();
            $table->dateTime('created_datetime');
            $table->bigInteger('last_updated_user_id')->unsigned()->nullable();
            $table->dateTime('last_Updated_DateTime')->nullable();
            $table->bigInteger('Posted_User_Id')->unsigned()->nullable();
            $table->dateTime('Posted_DateTime')->nullable();
            $table->string('Sys_ip');
            $table->timestamps();
            
            $table->foreign('jv_type_id')->references('id')->on('journal_voucher_types')->cascadeOnDelete();
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('Posted_User_Id')->references('id')->on('users');
            $table->foreign('last_updated_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_voucher_masters');
    }
};