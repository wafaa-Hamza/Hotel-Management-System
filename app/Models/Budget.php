<?php

namespace App\Models;

use App\Models\Month;
use App\Models\Ledger;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Budget extends Model
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

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }
    public function month()
    {
        return $this->belongsTo(Month::class);
    }
    public function transaction()
    {
        return $this->hasOne(transaction::class, 'ledger_id', 'id');
    }
}
