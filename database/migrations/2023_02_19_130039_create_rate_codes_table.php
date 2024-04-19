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
        Schema::create('rate_codes', function (Blueprint $table) {
            $table->id();
            $table->string('rate_code');
            $table->string('name');
            $table->string('name_loc');
            $table->date('start_date');
            $table->date('end_date');
            $table->boolean('active');
            $table->foreignId('meal_id')->nullable()->constrained();
            $table->foreignId('meal_package_id')->nullable()->constrained();
            $table->foreignId('ledger_id')->constrained();
            $table->foreignId('user_id')->constrained();
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
        Schema::dropIfExists('rate_codes');
    }
};
