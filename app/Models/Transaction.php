<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Budget;

use App\Models\Ledger;
use App\Models\Window;
use App\Models\LedgerCat;
use App\Models\Payment_type;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    protected $guarded = [];



    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Transaction has been {$eventName}");
    }
    public function ledger()
    {
        return $this->hasOne(Ledger::class, 'id', 'ledger_id');
    }
    // public function ledgerCat()
    // {
    //     return $this->hasManyThrough(LedgerCat::class,Ledger::class,'ledger_cat_id','id');
    // }
    public function guest()
    {
        return $this->hasOne(Guests::class, 'id', 'guest_id');
    }
    public function trGuestIDFrom()
    {
        return $this->hasOne(Guests::class, 'id', 'tr_guest_id_from');
    }
    public function created_by()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }
    public function updated_by()
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
    public function payment_type()
    {
        return $this->hasOne(Payment_type::class, 'id', 'payment_type_id');
    }
    public function window()
    {
        return $this->belongsTo(Window::class, 'window_id', 'id');
    }
    public function trWindowIDFrom()
    {
        return $this->belongsTo(Window::class, 'tr_window_id_from', 'id');
    }
    public function room()
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'id');
    }
    public function Budget()
    {
        return $this->hasOne(Budget::class, 'id', 'id');
    }
    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'transactions_taxes', 'transaction_id', 'tax_id')
            ->withPivot('id', 'amount', 'name', 'name_loc', 'inc')
            ->withTimestamps();
    }












    public function scopeSpecificYearToDate($q, $date)
    {

        return $q
            //->whereYear('hotel_date' , '<=', now())
            ->whereBetween('hotel_date', [Carbon::parse($date)->startOfYear(), Carbon::parse($date)])->count();
    }
    public function scopeSpecificMonthToDate($q, $date)
    {

        return $q->
        //whereMonth('hotel_date' , '<=', now())
        whereBetween('hotel_date', [Carbon::parse($date)->startOfMonth(), Carbon::parse($date)])
            ->count();
    }
    public function scopeLast7Days($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereDate('hotel_date', '>=', $date->subDays(7))->get();
    }
    public function scopeLast7DaysSumCharge($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereDate('hotel_date', '>=', $date->subDays(7))->sum('charge_amount');
    }
    public function scopeLast7DaysSumPayment($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereDate('hotel_date', '>=', $date->subDays(7))->sum('payment_amount');
    }
    public function scopeLastMonth($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereDate('hotel_date', '>=', $date->subMonth())->get();
    }
    public function scopeLastMonthSumCharge($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereDate('hotel_date', '>=', $date->subMonth())->sum('charge_amount');
    }
    public function scopeLastMonthSumPayment($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereDate('hotel_date', '>=', $date->subMonth())->sum('payment_amount');
    }
    public function scopeMonthToDate($query, $date)
    {

        return $query
            ->whereBetween('hotel_date', [Carbon::parse($date)->startOfMonth(), Carbon::parse($date)])->get();
    }
    public function scopeMonthToDateSumCharge($query, $date)
    {

        return $query
            ->whereBetween('hotel_date', [Carbon::parse($date)->startOfMonth(), Carbon::parse($date)])->sum('charge_amount');
    }
    public function scopeMonthToDateSumPayment($query, $date)
    {

        return $query
            ->whereBetween('hotel_date', [Carbon::parse($date)->startOfMonth(), Carbon::parse($date)])->sum('payment_amount');
    }
    public function scopeLastMonthToDate($query, $date)
    {

        return $query
            ->whereBetween('hotel_date', [Carbon::parse($date)->subYear()->startOfMonth(), Carbon::parse($date)->subYear()])->get();
    }
    public function scopeLastMonthToDateSumCharge($query, $date)
    {

        return $query
            ->whereBetween('hotel_date', [Carbon::parse($date)->subYear()->startOfMonth(), Carbon::parse($date)->subYear()])->sum('charge_amount');
    }
    public function scopeLastMonthToDateSumPayment($query, $date)
    {

        return $query
            ->whereBetween('hotel_date', [Carbon::parse($date)->subYear()->startOfMonth(), Carbon::parse($date)->subYear()])->sum('payment_amount');
    }


    public function scopeYearToDate($query, $date)
    {
        //  $date = Carbon::parse($date);
        return $query->whereYear('hotel_date', $date->year)
            ->whereBetween('hotel_date', [Carbon::parse($date)->startOfYear(), Carbon::parse($date)])->get();
    }
    public function scopeYearToDateSumCharge($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereYear('hotel_date', $date->year)
            ->whereBetween('hotel_date', [Carbon::parse($date)->startOfYear(), Carbon::parse($date)])->sum('charge_amount');
    }
    public function scopeYearToDateSumPayment($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereYear('hotel_date', $date->year)
            ->whereBetween('hotel_date', [Carbon::parse($date)->startOfYear(), Carbon::parse($date)])->sum('payment_amount');
    }
    public function scopeLastYearToDate($query, $date)
    {
        //$date = Carbon::parse($date);
        return $query->whereYear('hotel_date', $date->year)
            ->whereBetween('hotel_date', [Carbon::parse($date)->subYear()->startOfYear(), Carbon::parse($date)->subYear()])->get();
    }
    public function scopeLastYearToDateSumCharge($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereYear('hotel_date', $date->year)
            ->whereBetween('hotel_date', [Carbon::parse($date)->subYear()->startOfYear(), Carbon::parse($date)->subYear()])->sum('charge_amount');
    }
    public function scopeLastYearToDateSumPayment($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereYear('hotel_date', $date->year)
            ->whereBetween('hotel_date', [Carbon::parse($date)->subYear()->startOfYear(), Carbon::parse($date)->subYear()])->sum('payment_amount');
    }
    public function scopeMonth($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereMonth('hotel_date', $date->month)->get();
    }
    public function scopeMonthSumCharge($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereMonth('hotel_date', $date->month)->sum('charge_amount');
    }
    public function scopeMonthSumPayment($query, $date)
    {
        $date = Carbon::parse($date);
        return $query->whereMonth('hotel_date', $date->month)->sum('payment_amount');
    }

    public function transCollection()
    {
        return $this->hasMany(Transaction::class,'transaction_collection','id');
    }

}
