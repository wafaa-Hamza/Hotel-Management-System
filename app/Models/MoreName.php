<?php

namespace App\Models;

use App\Models\Country;
use App\Models\guest_profile;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MoreName extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "preCharge has been {$eventName}");
    }

    public function profile()
    {
        return $this->belongsTo(guest_profile::class, 'profile_id', 'id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }
    public function guest()
    {
        return $this->belongsTo(Guests::class, 'guest_id', 'id');
    }
}
