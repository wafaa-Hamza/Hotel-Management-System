<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExtraBedOrMeal extends Model
{
    use LogsActivity;
    use HasFactory;
    protected $guarded = ['id'];
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Floor has been {$eventName}");
    }

    public function ExtraBedMealPivoteGuest()
    {
        return $this->hasOne(ExtraBedAndMealPivotGuest::class);
    }

    public function ledger()
    {
        return $this->belongsTo(Ledger::class);
    }
}
