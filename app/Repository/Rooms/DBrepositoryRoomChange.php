<?php
namespace App\Repository\Rooms;

use App\Models\Room;
use App\Models\Room_change_history;
use App\Repositoryinterface\Rooms\RepositoryRoomChangeInterface;


class DBrepositoryRoomChange implements RepositoryRoomChangeInterface{
    private $roomModel; 
    private $roomChangeModel; 

    public function __construct(Room $roomModel,Room_change_history $roomChangeModel)
    {
        $this->roomModel = $roomModel;
        $this->roomChangeModel = $roomChangeModel;
    }

public function index()
{
    $roomChanges = $this->roomChangeModel->with(
       'guest',
        'user',
        'RoomChangeFrom',
        'RoomChangeTo'
        )->get();
    return $roomChanges;
}
public function roomChangPagination()
{
    $roomChanges = $this->roomChangeModel->with(
       'guest',
        'user',
        'RoomChangeFrom',
    'RoomChangeTo'
        )->paginate(request()->segment(count(request()->segments())));
    return $roomChanges;
}
public function store($request)
{
    
    $this->roomChangeModel->guest_id = $request->guest_id;
    $this->roomChangeModel->user_id = auth()->id();
    $this->roomChangeModel->from_room_number = $request->from_room_number;
    $this->roomChangeModel->to_room_number = $request->to_room_number;
    $this->roomChangeModel->reason = $request->reason;
    $this->roomChangeModel->hotel_date = date_create('now');
    $this->roomChangeModel->save();
    //dd()
    $roomChange =  $this->roomChangeModel::where('id',$this->roomChangeModel->id)->with( 
    'guest',
    'user',
    'RoomChangeFrom',
    'RoomChangeTo'
    )->get();
    return  $roomChange;
}
public function show($id)
{
   
    $room = $this->roomModel::where('id', $id)->with('room_tybe','floors')->first();
    return $room;
}
public function update($request, $id)
{
    $periodicity_type=$request->periodicity_type;
    $room = $this->roomModel::where('id', $id)->first();
    if($room){
        $this->roomModel::where('id',$id)->Update([
            'rm_name_en'  => (!empty($request['room_name_en'])) ? $request['room_name_en'] :  $room->rm_name_en,
            'rm_name_loc'  => (!empty($request['room_name_loc'])) ? $request['room_name_loc'] :  $room->rm_name_loc,
            'rm_max_pax'  => (!empty($request['room_max_pax'])) ? $request['room_max_pax'] :  $room->rm_max_pax,
            'rm_phone_no'  => (!empty($request['room_phone_no'])) ? $request['room_phone_no'] :  $room->rm_phone_no,
            'rm_phone_ext'  => (!empty($request['room_phone_ext'])) ? $request['room_phone_ext'] :  $room->rm_phone_ext,
            'rm_key_code'  => (!empty($request['room_key_code'])) ? $request['room_key_code'] :  $room->rm_key_code,
            'rm_key_options'  => (!empty($request['room_key_options'])) ? $request['room_key_options'] :  $room->rm_key_options,
            'rm_connection'  => (!empty($request['room_connection'])) ? $request['room_connection'] :  $room->rm_connection,
            'fo_status'  => (!empty($request['fo_status'])) ? $request['fo_status'] :  $room->fo_status,
            'rm_active'  => (!empty($request['room_active'])) ? $request['room_active'] :  $room->rm_active,
            'hk_stauts'  => (!empty($request['hk_stauts'])) ? $request['hk_stauts'] :  $room->hk_stauts,
            'sort_order'  => (!empty($request['sort_order'])) ? $request['sort_order'] :  $room->sort_order,
            'room_type_id'  => (!empty($request['room_type_id'])) ? $request['room_type_id'] :  $room->room_type_id,
            'floor_id'  => (!empty($request['floor_id'])) ? $request['floor_id'] :  $room->floor_id,
               
        ]);
        $room = $this->roomModel::where('id',$id)->with('room_tybe','floors')->get();
        return  $room;
    }else{
        return null;
    }       
}
public function destroy($id)
{
    $room=$this->roomModel::find($id);
    if($room){
      $room->delete();
        return $room;
    }else{
        return null;
    }
}
public function geSoftDeletedData()
{
   $roomsTrashed = $this->roomModel::onlyTrashed()->get();
   if($roomsTrashed){
        return $roomsTrashed;
   }else{
        return null;
   }
    

}
public function restorDataTrashed($id)
{
    $roomsTrashed = $this->roomModel::where('id', $id)->onlyTrashed()->get();
    if ($roomsTrashed) {
        $roomRestored = $this->roomModel::withTrashed()->where('id', $id)->restore();
        return $roomsTrashed;
    } else {
        return null;
    }
}


}