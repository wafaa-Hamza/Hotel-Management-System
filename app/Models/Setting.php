<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Setting extends Model
{
    use HasFactory;
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Setting has been {$eventName}");
    }
    protected $fillable = [
        'name',
        'name_loc',
        'hotel_date',
        'type',
        'cr_no',
        'phone',
        'mobile',
        'email',
        'city',
        'address',
        'vat_no',
    ];
}
