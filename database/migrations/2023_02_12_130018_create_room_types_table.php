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
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->string('rm_type_code');
            $table->string('rm_type_name');
            $table->string('rm_type_name_loc');
            $table->integer('def_pax');
            $table->float('def_price');
            $table->integer('no_of_rooms');

            $table->double('monthly_rate')->default(0);
            $table->double('yearly_rate')->default(0);

            $table->boolean('rm_type_rentable');
            $table->integer('sort_order');
            $table->integer('scth_type_code');
            $table->string('def_rate_code')->nullable();
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
        Schema::dropIfExists('room_types');
    }
};
