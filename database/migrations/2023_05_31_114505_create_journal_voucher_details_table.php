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
        Schema::create('journal_voucher_details', function (Blueprint $table) {
            $table->id();
            $table->year('fyear');
            $table->integer('fperiod');
            $table->unsignedBigInteger('jv_type_id');
            $table->unsignedBigInteger('jv_master_id');
            $table->integer('jv_no');
            $table->integer('jv_srl');
            $table->unsignedBigInteger('account_id');
            $table->string('reference')->nullable();
            $table->string('descriptions')->nullable();
            $table->decimal('debit')->default(0.00);
            $table->decimal('credit')->default(0.00);
            $table->timestamps();

            $table->foreign('jv_type_id')->references('id')->on('journal_voucher_types');
            $table->foreign('jv_master_id')->references('id')->on('journal_voucher_masters');
            $table->foreign('account_id')->references('id')->on('chart_of_accounts');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_voucher_details');
    }
};