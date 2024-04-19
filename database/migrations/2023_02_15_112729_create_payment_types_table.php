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
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->integer('code');
            $table->string('name');
            $table->string('name_loc');
            $table->string('type');
            $table->boolean('is_cash')->default(1);
            $table->double('commission_per')->default(0.00)->nullable();    // for future use
            $table->double('commission_amt')->default(0.00)->nullable();    //for future use
            $table->foreignId('payment_modes_id')->constrained()->cascadeOnDelete();        //related with payment mode
            $table->integer('scth_paymentId')->nullable();
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
        Schema::dropIfExists('payment_types');
    }
};