<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MealPackage extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Meal package has been {$eventName}");
    }
    public function meal()
    {
        return $this->belongsToMany(Meal::class, 'meals_packages', 'meal_package_id', 'meal_id')->withTimestamps();
    }
}
