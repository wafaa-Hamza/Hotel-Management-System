<?php

namespace App\Models;

use App\Models\Room;
use App\Models\User;
use App\Models\Guests;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room_change_history extends Model
{
  use HasFactory;
  use LogsActivity;

  protected $fillable = [
    'guest_id',
    'user_id',
    'from_room_number',
    'to_room_number',
    'reason',
    'hotel_date',
  ];
  //protected $model=['Room_change_history'];

  public function getActivitylogOptions(): LogOptions
  {
    return LogOptions::defaults()
      ->logAll()->logOnlyDirty()
      ->setDescriptionForEvent(fn (string $eventName) => "Room_change_history has been {$eventName}");
  }

  public function guest()
  {
    return $this->hasMany(Guests::class, 'id', 'guest_id');
  }


  public function user()
  {
    return $this->hasMany(User::class, 'id', 'user_id');
  }

  public function RoomChangeFrom()
  {

    return $this->hasMany(Room::class, 'id', 'from_room_number');
  }

  public function RoomChangeTo()
  {

    return $this->hasMany(Room::class, 'id', 'to_room_number');
  }
}
