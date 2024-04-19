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
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->string('folio_no', 100)->nullable();
            $table->date('in_date');
            $table->dateTime('out_date');
            $table->date('original_out_date');
            $table->integer('no_of_nights');
            $table->float('rm_rate');
            $table->float('taxes')->nullable();
            $table->integer('no_of_pax');
            $table->text('hotel_note')->nullable();
            $table->text('client_note')->nullable();
            $table->string('way_of_payment', 50);
            $table->bigInteger('profile_id')->unsigned();
            $table->bigInteger('company_id')->unsigned();
            $table->string('company_name')->nullable();
            $table->bigInteger('room_id')->unsigned()->nullable();
            $table->bigInteger('room_type_id')->unsigned();
            $table->bigInteger('rate_code_id')->unsigned()->nullable();
            $table->bigInteger('market_segment_id')->unsigned();
            $table->bigInteger('source_of_business_id')->unsigned();
            $table->bigInteger('meal_id')->unsigned()->nullable();
            $table->bigInteger('meal_package_id')->unsigned()->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->dateTime('created_inhousGuest_at')->nullable(); //this is date of checked_in
            $table->boolean('checked_out')->nullable();
            $table->bigInteger('checkout_by')->unsigned()->nullable();
            $table->dateTime('checkout_at')->nullable();
            $table->boolean('is_reservation')->default(1);
            $table->string('res_status', 20);
            $table->string('res_no', 100)->nullable();
            $table->boolean('is_group')->default(0);
            $table->bigInteger('group_code')->nullable();
            $table->boolean('is_dummy')->default(0);
            $table->boolean('Is_PM')->default(0);
            $table->boolean('is_cancel')->default(0);
            $table->dateTime('cancel_date')->nullable();
            $table->boolean('is_checked_in')->default(0);
            $table->string('ref_no', 50)->nullable();
            $table->boolean('is_online')->default(0);
            $table->boolean('is_join')->default(0);
            $table->boolean('is_connected')->default(0);
            $table->boolean('locked')->default(0);
            $table->string('vip', 1)->nullable()->default(null);
            $table->bigInteger('join_to')->unsigned()->nullable();
            $table->string('vat_no')->nullable();
            $table->string('res_type',1)->nullable();
            $table->string('inv_address')->nullable();
            $table->string('scth_transaction_id')->nullable();
            $table->integer('customer_type');
            $table->integer('purpose_of_visit');

            $table->boolean('is_sent_shomoos')->default(false);
            $table->string('shomoos_id')->nullable();
            $table->integer('no_of_child')->default(0);
            $table->integer('no_of_inf')->default(0);

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
        Schema::dropIfExists('guests');
    }
};
