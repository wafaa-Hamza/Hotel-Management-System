<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandlordTenantsTable extends Migration
{
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('clientname');
            $table->string('tenantname');
            $table->string('email');
            $table->string('address');
            $table->string('taxnumber')->nullable();
            $table->boolean('is_auto_close')->nullable()->default(0);
            $table->boolean('is_shomoos')->default(0);
            $table->time('close_time')->default("03:00");
            $table->foreignId('language_id')->constrained()->restrictOnDelete()->nullable();
            $table->string('domain')->unique();
            $table->string('database')->unique();
            $table->timestamps();
        });
    }
}
