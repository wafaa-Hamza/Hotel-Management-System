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
        Schema::create('integrations', function (Blueprint $table) {
            $table->id();
            $table->string('name',60);
            $table->string('name_loc',60);
            $table->string('type',60);
            $table->string('integration_api');
            $table->string('integration_local_api');
            $table->string('Content-Type');
            $table->string('x-gatewa-api_key');
            $table->string('authorization');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('integrations');
    }
};
