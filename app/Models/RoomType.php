<?php

namespace App\Models;

use App\Models\Room;
use App\Models\RateCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class RoomType extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->logAll()->logOnlyDirty()
            ->logExcept(['updated_at', 'created_at'])
            ->setDescriptionForEvent(fn (string $eventName) => "RoomType has been {$eventName}");
    }
    protected $dates = ['deleted_at'];
    protected $fillable = ['rm_type_code', 'rm_type_name', 'rm_type_name_loc', 'def_pax', 'def_price', 'no_of_rooms', 'rm_type_rentable', 'sort_order', 'scth_type_code', 'def_rate_code'];

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
    public function guest()
    {
        return $this->hasMany(Guests::class);
    }
}
