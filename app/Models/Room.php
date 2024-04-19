<?php

namespace App\Models;

use App\Models\Floor;
use App\Models\Guests;

use App\Models\RoomType;

use App\Models\RoomStatus;
use App\Models\OordService;

use App\Models\guest_profile;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Room extends Model
{
    use HasFactory, SoftDeletes;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()->logOnlyDirty()
            ->setDescriptionForEvent(fn (string $eventName) => "Room ({$this->rm_name_en}) has been {$eventName}");
    }

    protected $fillable = [
        'id',
        'rm_name_en',
        'rm_name_loc',
        'rm_max_pax',
        'rm_phone_no',
        'rm_phone_ext',
        'rm_key_code',
        'rm_key_options',
        'rm_connection',
        'fo_status',
        'hk_stauts',
        'rm_active',
        'sort_order',
        'room_type_id',
        'floor_id',
        'view_id',
        'room_statuse_id',
    ];

    public function room_type()
    {
        return $this->belongsTo(RoomType::class, 'room_type_id', 'id');
    }

    public function floors()
    {
        return $this->hasOne(Floor::class, 'id', 'floor_id');
    }


    public function guests()
    {
        return $this->hasOne(Guests::class);
    }
    public function reservation()
    {
        return $this->hasOne(Guests::class)->where('is_reservation', 1);
    }

    public function guest_profile()
    {
        return $this->hasOne(guest_profile::class);
    }


    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'room_id', 'id');
    }

    public function OordService()

    {
        return $this->hasOne(OordService::class);
    }
    public function ViewGardens()
    {
        return $this->hasOne(ViewGarden::class, 'id', 'view_id');
    }

    public  static function excludeDummyRooms($with = [])
    {
        $dummyRooms = static::query()->where('rm_max_pax', '!=', 0)->with($with);
        return $dummyRooms;
    }

    //  public function roomStatus()


    //   public function roomStatus() {
    //      return $this->hasOne(RoomStatus::class,'status_code','fo_status' . 'hk_stauts');
    //  }

    public function roomStatus()
    {
        return $this->belongsTo(RoomStatus::class,  'room_statuse_id','id');
    }


    //     public static function  scopeMax_sortOrder()
    //     {
    //  $max_sort=room::max('sort_order')->get();
    // dd($max_sort);

    //     public function scopeStatus(){
    //         $data = RoomStatus::where(function($q){

    //             $status = Room::where('id',$this->id)->get();
    //             $roomData=[];
    //           // $collectFoStatus = collect($foStatus)->flatten()->all();
    //            //$statusAfterImplode = implode('', $collectFoStatus);
    //            //dd($statusAfterImplode);
    //            foreach( $status as $oneStatus)
    //            {
    //            // dd([$oneStatus->fo_status.$oneStatus->hk_stauts]);
    //             $q->orWhere('status_code',$oneStatus->fo_status.$oneStatus->hk_stauts);
    //            // dd($q->get());
    //             $oneStatus->map(function($item)use($q){
    //                return  $item = $q->get();
    //              });
    //                // array_push($roomData,$oneStatus);
    //            }
    //            // return $q;
    //         });

    // dd($data);


    //     }

}
