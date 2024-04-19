<?php

namespace App\Models;

use App\Models\User;
use App\Models\Guests;
use App\Models\Window;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
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
    public function window()
    {
        return $this->belongsTo(Window::class, 'window_id', 'id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // public function setUuidAttribute($value)
    // {
    //     $this->attributes['uuid'] = $value ?: Str::uuid();
    // }

}
