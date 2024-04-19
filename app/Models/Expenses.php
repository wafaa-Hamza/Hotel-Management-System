<?php

namespace App\Models;

use App\Models\ExpensesLedger;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Expenses extends Model
{
    use LogsActivity, HasFactory;

    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Floor has been {$eventName}");
    }

    public function ledger()
    {
        return $this->belongsTo(ExpensesLedger::class, 'exp_ledger_id', 'id');
    }
}
