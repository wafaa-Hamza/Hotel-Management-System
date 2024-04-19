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
        Schema::create('public_hotel_pages', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('company_name_loc');
            $table->string('hotel_name');
            $table->string('hotel_name_loc');
            $table->string('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('description')->nullable();
            $table->string('location')->nullable();
            $table->string('booking_page')->nullable();
            $table->string('facebook')->nullable();
            $table->string('website')->nullable();
            $table->string('CR_no')->nullable();
            $table->string('vat_no')->nullable();
            $table->string('logo')->nullable();
            $table->string('picture1')->nullable();
            $table->string('picture2')->nullable();
            $table->string('picture3')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_hotel_pages');
    }
};
