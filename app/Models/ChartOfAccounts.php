<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ChartOfAccounts extends Model
{
    use LogsActivity;
    use HasFactory;

    protected $fillable = [
        'account_code', 'main_account_id', 'account_name', 'account_name_loc', 'account_level', 'account_class',
        'account_type', 'is_transaction'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Country  has been {$eventName}");
    }

    public function mainAccount()
    {
        return $this->belongsTo(ChartOfAccounts::class, 'id', 'main_account_id');
    }
}
