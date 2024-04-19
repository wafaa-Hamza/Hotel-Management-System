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
        Schema::create('guest_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('Profile_no')->unique();
            $table->string('first_name',50);
            $table->string('last_name',50);
            $table->string('id_no',100)->unique();
            $table->string('mobile',50);
            $table->string('phone',50)->nullable();
            $table->string('email',100)->unique()->nullable();
            $table->string('address')->nullable();
            $table->bigInteger('country_id')->unsigned();
            $table->string('city')->nullable();
            $table->string('language')->nullable();
            $table->string('date_of_birth',10)->nullable();
            $table->string('gender');
            $table->integer('group_code')->nullable();
            $table->bigInteger('created_by')->unsigned();
            $table->bigInteger('updated_by')->unsigned();

            $table->bigInteger('id_type_id')->unsigned();
            $table->integer('version_no')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('created_by')->references('id')->on('users');  
            $table->foreign('updated_by')->references('id')->on('users');  
            $table->foreign('id_type_id')->references('id')->on('id_types')->cascadeOnDelete();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guest_profiles');
    }
};
