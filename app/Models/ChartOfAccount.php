<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use App\Models\ChartOfAccountLevel;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChartOfAccount extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = ['id'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Meal has been {$eventName}");
    }

    public function level()
    {
        return $this->belongsTo(ChartOfAccountLevel::class, 'account_level', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(ChartOfAccount::class, 'id', 'main_account_id');
    }

    public function child()
    {
        return $this->hasMany(ChartOfAccount::class, 'main_account_id', 'id');
    }

    // public function scopeBalanceWithChilds($query, $startDate, $endDate)
    // {


    //   //  $ids = ChartOfAccounts::where('account_code', 'like', $this->account_code . '%')->get(['id']);

    //     $account_balanceDebit = DB::table('journal_voucher_masters')
    //         ->join('journal_voucher_details', 'journal_voucher_details.jv_master_id', '=', 'journal_voucher_masters.id')
    //         ->whereBetween('journal_voucher_masters.jv_date', [$startDate, $endDate])
    //       //  ->whereIn('journal_voucher_details.account_id', $ids)
    //         ->sum('journal_voucher_details.debit');


    //     $account_balanceCredit = DB::table('journal_voucher_masters')
    //         ->join('journal_voucher_details', 'journal_voucher_details.jv_master_id', '=', 'journal_voucher_masters.id')
    //         ->whereBetween('journal_voucher_masters.jv_date', [$startDate, $endDate])
    //        // ->whereIn('journal_voucher_details.account_id', $ids)
    //         ->sum('journal_voucher_details.credit');

    //     //  $Balance = $account_balanceDebit - $account_balanceCredit;

    //     $OpenDebit = DB::table('journal_voucher_masters')
    //         ->join('journal_voucher_details', 'journal_voucher_details.jv_master_id', '=', 'journal_voucher_masters.id')
    //         ->whereDate('journal_voucher_masters.jv_date', '<', $startDate)
    //         //->whereIn('journal_voucher_details.account_id', $ids)
    //         ->sum('journal_voucher_details.debit');

    //     //dd($startDate);
    //     $OpenCredit = DB::table('journal_voucher_masters')
    //         ->join('journal_voucher_details', 'journal_voucher_details.jv_master_id', '=', 'journal_voucher_masters.id')
    //         ->whereDate('journal_voucher_masters.jv_date', '<', $startDate)
    //        // ->whereIn('journal_voucher_details.account_id', $ids)
    //         ->sum('journal_voucher_details.credit');


    //     //  $Balance = $account_balanceDebit - $account_balanceCredit;

    //     $data = [
    //         'codeName'                 => $this,
    //         'account_balanceDebit'     => $account_balanceDebit,
    //         'account_balanceCredit'    => $account_balanceCredit,
    //         // 'Balance'                  => $Balance,
    //         'OpenDebit'                => $OpenDebit,
    //         'OpenCredit'               => $OpenCredit

    //     ];


    //     return  $data;
    // }

    public function scopeBalanceSheet($query, $startDate, $endDate)
    {
        $profits_debit_sheet = JournalVoucherMaster::join('journal_voucher_details', 'journal_voucher_details.jv_master_id', '=', 'journal_voucher_masters.id')
            ->whereBetween('jv_date', [$startDate, $endDate])
            ->where('journal_voucher_details.account_id', $this->id)
            ->sum('journal_voucher_details.debit');

        //return  $profits_debit;
        $profits_credit_sheet = JournalVoucherMaster::join('journal_voucher_details', 'journal_voucher_details.jv_master_id', '=', 'journal_voucher_masters.id')
            ->whereBetween('jv_date', [$startDate, $endDate])
            ->where('journal_voucher_details.account_id', $this->id)
            ->sum('journal_voucher_details.credit');


        $balance = $profits_debit_sheet - $profits_credit_sheet;
        return $balance;
    }
}
