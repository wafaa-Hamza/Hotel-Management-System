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
        Schema::create('extra_bed_and_meal_pivot_guests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('extra_id')->unsigned();
            $table->bigInteger('guest_id')->unsigned();
            $table->double('amount')->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extra_bed_and_meal_pivot_guests');
    }
};
