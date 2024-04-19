<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResRateDay extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function guest()
    {
       return $this->belongsTo(Guests::class,'guest_id', 'id');
    }

    public function rateCode()
    {
       return $this->belongsTo(RateCode::class,'rate_id', 'id');
    }
    public function meal()
    {
       return $this->belongsTo(Meal::class,'meal_id', 'id');
    }

    public function mealPackage()
    {
       return $this->belongsTo(MealPackage::class,'meal_package_id', 'id');
    }


}
