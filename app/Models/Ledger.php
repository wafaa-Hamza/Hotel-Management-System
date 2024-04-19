<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Ledger extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Ledger has been {$eventName}");
    }
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'code', 'name', 'name_loc', 'type', 'ledger_cat_id',
    ];
    public function inctaxes()
    {
        return $this->belongsToMany(Tax::class, 'ledgers_taxes', 'ledger_id', 'tax_id')->withPivot('inc')->wherePivot('inc', '=', 1)
            ->withTimestamps();
    }
    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'ledgers_taxes', 'ledger_id', 'tax_id')->withPivot('inc')->wherePivot('inc', '=', 0)
            ->withTimestamps();
    }
    public function ledger_cat()
    {
        return $this->belongsTo(LedgerCat::class, 'ledger_cat_id');
    }
    public function meals()
    {
        return $this->hasMany(Meal::class);
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'ledger_id', 'id');
    }
    public function transactionsMtd()
    {
        return $this->hasMany(Transaction::class, 'ledger_id', 'id');
    }
    public function transactionsYtd()
    {
        return $this->hasMany(Transaction::class, 'ledger_id', 'id');
    }

    public function transactionsMtdLedCat()
    {
        return $this->hasMany(Transaction::class, 'ledger_id', 'ledger_cat_id');
    }

    public function Budgets()
    {
        return $this->belongsTo(Budget::class, 'id', 'ledger_id');
    }
}
