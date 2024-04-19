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
        Schema::create('company_AR_statements', function (Blueprint $table) {
            $table->id();
            $table->integer('invoice_no')->nullable();
            $table->bigInteger('guest_id')->unsigned()->nullable();
            $table->bigInteger('company_id')->unsigned();
            $table->bigInteger('payment_type_id')->unsigned()->nullable();
            $table->dateTime('trans_date');
            $table->integer('trans_no');
            $table->string('trans_type');
            $table->string('ref_no')->nullable();
            $table->text('description');
            $table->decimal('debit_amount');
            $table->decimal('credit_amount');
            $table->decimal('paid_amount')->nullable();;
            $table->string('is_paid')->nullable();;
            $table->bigInteger('created_by')->unsigned();
            $table->boolean('void')->nullable();
            $table->dateTime('void_datetime')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('company_AR_statements');
    }
};
