<?php

namespace App\Models;

use App\Models\Tax;
use App\Models\Ledger;
use App\Models\Payment_type;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class transactionHistory extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    protected $guarded = [];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "transactionHistory has been {$eventName}");
    }
    public function ledger()
    {
        return $this->hasOne(Ledger::class, 'id', 'ledger_id');
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
    public function taxes()
    {
        return $this->belongsToMany(Tax::class, 'transaction_taxe_histories', 'transaction_history_id', 'tax_id')
            ->withPivot('id', 'amount', 'name', 'name_loc', 'inc')
            ->withTimestamps();
    }
}
