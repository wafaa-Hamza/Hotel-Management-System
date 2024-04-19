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
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('taxnumber')->nullable();
            $table->foreignId('language_id')->constrained()->restrictOnDelete();
            $table->string('domain')->unique();
            $table->string('database')->unique();
            $table->timestamps();
        });
    }
}
