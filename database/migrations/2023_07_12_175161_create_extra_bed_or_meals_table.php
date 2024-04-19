<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('extra_bed_or_meals', function (Blueprint $table) {
            $table->id();
             $table->string('name',50);
            $table->string('name_loc',50);
            $table->bigInteger('ledger_id')->unsigned();
            $table->timestamps();
            
            $table->foreign('ledger_id')->references('id')->on('ledgers');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_bed_or_meals');
    }
};