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
        Schema::create('black_lists', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('profile_id')->nullable();
            $table->string('id_no');
            $table->string('mobile_no');
            $table->text('blacklist_reason');
            $table->bigInteger('user_id');     //Tenant Only
            $table->string('created_by');               //landlord & Tenant
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
        Schema::dropIfExists('black_lists');
    }
};
