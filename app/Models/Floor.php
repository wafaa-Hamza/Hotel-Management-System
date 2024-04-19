<?php

namespace App\Models;

use App\Models\Tower;
use App\Models\Language;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Floor extends Model
{
    use SoftDeletes;
    use LogsActivity;
    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Floor has been {$eventName}");
    }
    public function rooms()
    {
        return $this->hasMany(Room::class, 'floor_id', 'id');
    }

    public function tower()
    {
        return $this->belongsTo(Tower::class);
    }
}
