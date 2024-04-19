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
        Schema::create('statements', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('folio_no');
            $table->bigInteger('company_id')->unsigned();
            $table->date('trans_date');
            $table->string('trans_no');
            $table->string('trans_type');
            $table->string('ref_no')->nullable();
            $table->text('description');
            $table->string('debit_amount');
            $table->string('credit_amount');
            $table->bigInteger('created_by')->unsigned();
            $table->boolean('void');
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
        Schema::dropIfExists('statements');
    }
};