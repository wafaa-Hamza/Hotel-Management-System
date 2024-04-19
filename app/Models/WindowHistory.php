<?php

namespace App\Models;

use App\Models\Guests;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WindowHistory extends Model
{
    use HasFactory;
    // use LogsActivity;
    protected $fillable = [
        'guest_id',
        'window_number',
        'window_name',
        'invoice_number',
    ];
    // public function getActivitylogOptions(): LogOptions
    // {
    //     return LogOptions::defaults()
    //         ->logAll()->logOnlyDirty()
    //         ->setDescriptionForEvent(fn(string $eventName) => "Window  has been {$eventName}");
    // }
    public function guest()
    {
        return $this->hasMany(Guests::class, 'id', 'guest_id');
    }
}
