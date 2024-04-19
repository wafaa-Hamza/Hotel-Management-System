<?php

namespace App\Models;

use App\Models\Guests;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GuestAttachment extends Model
{
    use LogsActivity;
    use HasFactory;
    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => " Guests has been {$eventName}");
    }

    public function guest()
    {
        return $this->belongsTo(Guests::class, 'guest_id', 'id');
    }
}
