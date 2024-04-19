<?php

namespace App\Models;

use App\Models\Meal;
use App\Models\Room;
use App\Models\User;
use App\Models\Guests;
use App\Models\RateCode;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ratechange extends Model
{
   use HasFactory;
   use LogsActivity;



   protected  $fillable = [

      'guest_id',
      'hotel_date',
      'from_rate_code_id',
      'to_rate_code_id',
      'from_rm_rate',
      'to_rm_rate',
      'room_id',
      'reason',
      'created_by',

   ];


   public function getActivitylogOptions(): LogOptions
   {
      return LogOptions::defaults()
         ->logAll()->logOnlyDirty()
         ->setDescriptionForEvent(fn (string $eventName) => "Ratechange has been {$eventName}");
   }
   ////////////////////////////////////////////////////////////////////////////
   public function guest()
   {
      return $this->hasOne(Guests::class, 'id', 'guest_id');
   }
   ////////////////////////////////////////////////////////////////////////////////
   public function users()
   {
      return $this->hasMany(User::class, 'id', 'created_by');
   }
   /////////////////////////////////////////////////////////////////////////////
   public function room()
   {
      return $this->hasOne(Room::class, 'id', 'room_id');
   }

   public function ratecode()
   {
      return $this->hasOne(RateCode::class, 'id', 'rate_code_id');
   }
   public function RateCodeFrom()
   {

      return $this->hasMany(RateCode::class, 'id', 'from_rate_code_id');
   }

   public function RateCodeTo()
   {

      return $this->hasMany(RateCode::class, 'id', 'to_rate_code_id');
   }


   public function meals()
   {
      return $this->hasOne(Meal::class, 'id', 'meal_id');
   }
}
