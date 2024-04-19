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
        Schema::create('day_close_records', function (Blueprint $table) {
            $table->id();
            $table->dateTime('hotel_date');
            $table->dateTime('sys_date');
            $table->integer('total_room');
            $table->integer('os_rooms');
            $table->integer('oo_rooms');
            $table->integer('oc_rooms');
            $table->integer('va_rooms');
            $table->integer('comp_rooms')->nullable();
            $table->integer('house_use_rooms')->nullable();
            $table->double('guest_ledger');
            $table->double('pre_bill_chrg');
            $table->double('dcl_balance');
            $table->double('cl_transfare');
            $table->double('cl_payment');
            $table->integer('guest_inhouse_pax');
            $table->integer('act_chkin_rooms');
            $table->integer('act_chkin_pax');
            $table->integer('act_chkout_rooms');
            $table->integer('act_chkout_pax');
            $table->integer('guest_group');
            $table->integer('no_of_vip');
            $table->integer('res_today');
            $table->integer('res_cancel');
            $table->integer('res_noshow_rooms');
            $table->integer('res_noshow_pax');
            $table->integer('early_chkout_rooms');
            $table->integer('early_chkout_pax');
            $table->integer('extended_rooms');
            $table->integer('extended_pax');
            $table->integer('walkin_rooms');
            $table->integer('walkin_pax');
            $table->integer('day_use_rooms');
            $table->integer('day_use_pax');
            $table->integer('expt_in_tmrw_rooms');
            $table->integer('expt_in_tmrw_pax');
            $table->integer('expt_out_tmrw_rooms');
            $table->integer('expt_out_tmrw_pax');
            $table->double('total_rate_room');
            $table->double('total_fb');
            $table->double('total_taxes');
            $table->double('total_others');
            $table->double('total_paymants');
            $table->double('ind_rate');
            $table->double('group_rate');  
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
        Schema::dropIfExists('day_close_records');
    }
};