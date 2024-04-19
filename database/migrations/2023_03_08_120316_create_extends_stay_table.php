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
        Schema::create('extends_stay', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('guest_id')->unsigned();
            $table->dateTime('out_date_from');
            $table->dateTime('out_date_to');
            $table->bigInteger('done_by')->unsigned();
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
        Schema::dropIfExists('extends_stay');
    }
};
