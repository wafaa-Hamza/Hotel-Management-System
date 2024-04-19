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
        Schema::create('shomoos', function (Blueprint $table) {
            $table->id();
            $table->string('response_code')->default('0');
            $table->string('response_message')->default('0');
            $table->text('request');
            $table->string('api');
            $table->foreignId('guest_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shomoos');
    }
};
