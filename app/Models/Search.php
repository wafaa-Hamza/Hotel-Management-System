<?php

namespace App\Models;

use App\Models\Guests;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Search extends Model
{
    use HasFactory;
    use LogsActivity;
    protected $guarded = [];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Search  has been {$eventName}");
    }

    public function profile()
    {
        return $this->hasMany(guest_profile::class, 'id', 'id');
    }
    public function guest()
    {
        return $this->hasOne(Guests::class, 'id', 'guest_id');
    }
}
