<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class RateCode extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Ratecode has been {$eventName}");
    }
    public function meal()
    {
        return $this->hasOne(Meal::class, 'id', 'meal_id');
    }
    public function meal_package()
    {
        return $this->hasOne(MealPackage::class, 'id', 'meal_package_id');
    }
    public function ledger()
    {
        return $this->hasOne(Ledger::class, 'id', 'ledger_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
    public function roomTypes()
    {
        return $this->belongsToMany(RoomType::class, 'ratecodes_roomtypes', 'rate_code_id', 'room_type_id')
            ->withPivot('pax1', 'pax2', 'pax3', 'pax4', 'pax5', 'pax6', 'extra_pax')
            ->withTimestamps();
    }
    public function roomTypess($id)
    {
        return $this->belongsToMany(RoomType::class, 'ratecodes_roomtypes', 'rate_code_id', 'room_type_id')
            ->where('room_type_id',)
            ->withPivot('pax1', 'pax2', 'pax3', 'pax4', 'pax5', 'pax6', 'extra_pax')
            ->withTimestamps();
    }
}
