<?php

namespace App\Models;

use App\Models\Ledger;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class LedgerCat extends Model
{
    use HasFactory;
    use LogsActivity;
    use SoftDeletes;


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => " LedgerCat  has been {$eventName}");
    }

    public function ledgers()
    {
        return $this->hasMany(Ledger::class, 'ledger_cat_id', 'id');
    }

    public function transactionsLedCat()
    {
        return $this->hasManyThrough(Transaction::class, Ledger::class);
    }
    public function transactionsMtdLedCat()
    {
        return $this->hasManyThrough(Transaction::class, Ledger::class);
    }
    public function transactionsYtdLedCat()
    {
        return $this->hasManyThrough(Transaction::class, Ledger::class);
    }

    public function dayCloseSalesLedCat()
    {
        return $this->hasManyThrough(closeSales::class, Ledger::class);
    }
}
