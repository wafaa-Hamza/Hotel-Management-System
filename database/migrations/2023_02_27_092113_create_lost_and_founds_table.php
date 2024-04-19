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
        Schema::create('lost_and_founds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained()->nullable()->cascadeOnDelete();
            $table->text('description');
            $table->date('found_date');
            $table->string('found_by');
            $table->string('delivery_status');
            $table->date('delivery_date')->nullable();
            $table->UnsignedBigInteger('delivery_by')->nullable();
            $table->foreign('delivery_by')->references('id')->on('users');
            $table->string('delivery_to')->nullable();
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
        Schema::dropIfExists('lost_and_founds');
    }
};
