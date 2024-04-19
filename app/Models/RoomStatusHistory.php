<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RoomStatusHistory extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Floor has been {$eventName}");
    }
    public function users()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
    public function Rooms()
    {
        return $this->hasMany(Room::class, 'id', 'room_id');
    }
}
