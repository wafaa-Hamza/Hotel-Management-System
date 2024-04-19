<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExtraBedAndMealPivotGuest extends Model
{
    use LogsActivity, HasFactory;
    protected $guarded = ['id'];
    // protected $table = 'extra_bed_and_meal_pivot_guest_extra_bed_or_meal';
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Floor has been {$eventName}");
    }

    public function ExtraBedMeal()
    {
        return $this->belongsTo(ExtraBedOrMeal::class, 'extra_id', 'id');
    }

    public function guest()
    {
        return $this->belongsTo(Guests::class, 'guest_id', 'id');
    }
}
