<?php

namespace App\Models;


use App\Models\Room;
use App\Models\User;
use Spatie\Activitylog\LogOptions;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OordService extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;


    protected  $fillable = [
        'room_id',
        'oord_typ',        //(will be OO, OS)
        'start_date',
        'end_date',
        'notes',
        'created_by',       //(user_id)
        'is_return',       //(boolean) default 0
        'return_by',      //(user_id)
        'return_date',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "OordService has been {$eventName}");
    }
    public function created_by()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function return_by()
    {
        return $this->hasOne(User::class, 'id', 'return_by');
    }

    public function room()
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
}
