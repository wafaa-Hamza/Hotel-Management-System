<?php

namespace App\Models;

use App\Models\Room;
use App\Models\User;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Discrepancy extends Model
{
    use HasFactory;
    use LogsActivity;
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Room has been {$eventName}");
    }

    protected $guarded = ['id'];

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
    public function solvedBy()
    {
        return $this->belongsTo(User::class, 'solved_by', 'id');
    }
}
