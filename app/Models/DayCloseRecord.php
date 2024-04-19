<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class DayCloseRecord extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'hotel_date',
        'sys_date',
        'total_room',
        'os_rooms',
        'oo_rooms',
        'oc_rooms',
        'va_rooms',
        'comp_rooms',
        'house_use_rooms',
        'guest_ledger',
        'pre_bill_chrg',
        'dcl_balance',
        'cl_transfare',
        'cl_payment',
        'guest_inhouse_pax',
        'act_chkin_rooms',
        'act_chkin_pax',
        'act_chkout_rooms',
        'act_chkout_pax',
        'guest_group',
        'no_of_vip',
        'res_today',
        'res_cancel',
        'res_noshow_rooms',
        'res_noshow_pax',
        'early_chkout_rooms',
        'early_chkout_pax',
        'extended_rooms',
        'extended_pax',
        'walkin_rooms',
        'day_use_rooms',
        'day_use_pax',
        'expt_in_tmrw_rooms',
        'expt_in_tmrw_pax',
        'expt_out_tmrw_rooms',
        'expt_out_tmrw_pax',
        'total_rate_room',
        'total_fb',
        'total_taxes',
        'total_others',
        'total_paymants',
        'ind_rate',
        'group_rate',
        'walkin_pax',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "__(DayCloseRecord has been {$eventName})");
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'id', 'id');
    }
}
