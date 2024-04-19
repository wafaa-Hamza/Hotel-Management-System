<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use App\Models\JournalVoucherMaster;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalVoucherDetail extends Model
{
    use LogsActivity, HasFactory;

    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Floor has been {$eventName}");
    }

    public function journalVoucherType()
    {
        return $this->belongsTo(JournalVoucherType::class, 'id', 'jv_master_id');
    }
    public function journalVoucherMaster()
    {
        return $this->belongsTo(JournalVoucherMaster::class, 'id', 'jv_type_id');
    }

    public function chartOfAccount()
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id', 'id');
    }
}
