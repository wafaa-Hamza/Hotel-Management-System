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
        Schema::create('oord_services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('room_id')->unsigned();
            $table->string('oord_type');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('notes')->nullable();
            $table->bigInteger('created_by')->unsigned();
            $table->boolean('is_return')->default(0);
            $table->bigInteger('return_by')->nullable()->unsigned();
            $table->dateTime('return_date')->nullable();
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
        Schema::dropIfExists('oord_services');
    }
};
