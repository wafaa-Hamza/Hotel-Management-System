<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JournalVoucherType extends Model
{
    use LogsActivity, HasFactory;

    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Floor has been {$eventName}");
    }

    public function journalVoucher()
    {
        return $this->hasOne(JournalVoucherMaster::class, 'id', 'jv_type_id');
    }
}
