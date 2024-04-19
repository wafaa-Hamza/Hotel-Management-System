<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Meal extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;
    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Meal has been {$eventName}");
    }
    public function ledger()
    {
        return $this->belongsTo(Ledger::class, 'ledger_id', 'id');
    }

    public function meal_package()
    {
        return $this->belongsToMany(MealPackage::class, 'meals_packages', 'meal_id', 'meal_package_id')->withTimestamps();
    }
}
