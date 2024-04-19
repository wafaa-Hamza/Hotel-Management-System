<?php

namespace App\Models;

use Carbon\Carbon;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Maintenance extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Maintenance has been {$eventName}");
    }

    public function room()
    {
        return $this->hasOne(Room::class, 'id', 'room_id');
    }
    public function maintenance_type()
    {
        return $this->hasOne(MaintenanceType::class, 'id', 'maintenance_type_id');
    }
    public function isCreatedDateBetween($startDate, $endDate)
    {
        $createdAt = Carbon::parse($this->created_at);
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        return $createdAt->between($start, $end);
    }
}
