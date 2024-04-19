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
        Schema::create('journal_voucher_types', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('name_loc',50);
            $table->year('fyear');
            $table->integer('last_serial_no')->default(0);
            $table->boolean('is_opening')->default(false);
            $table->string('voucher_type',3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_voucher_types');
    }
};