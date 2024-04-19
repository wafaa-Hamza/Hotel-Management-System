<?php

namespace App\Repository\Rooms;

use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositoryinterface\Generalinterface;
use App\Repositoryinterface\Rooms\RepositoryRoomInterface;

class DBrepositoryRoom implements RepositoryRoomInterface
{
    private $roomModel;
    private $floorModel;
    private $generalInterface;

    public function __construct(Room $roomModel, Generalinterface $generalInterface, Floor $floorModel)
    {
        $this->roomModel = $roomModel;
        $this->floorModel = $floorModel;
        $this->generalInterface = $generalInterface;
    }

    public function index()
    {
        //  dd('mmm');
        ///   $rooms = $this->roomModel->with('room_type', 'floors', 'roomStatus')->get();
        //  dd($rooms);
        $rooms = $this->roomModel->excludeDummyRooms(['floors', 'room_type', 'roomStatus'])->get()->load('floors', 'room_type', 'roomStatus');;
        return $rooms;
    }
    public function roomPagination()
    {
        $rooms = $this->roomModel->with('room_type', 'floors')->paginate(request()->segment(count(request()->segments())));
        //  dd($rooms);
        return $rooms;
    }
    public function Max_sortOrder()
    {
        $get_sort = Room::max('sort_order');
        return $get_sort;
    }
    public function store($request)
    {
        $room = $this->roomModel::create([
            'rm_name_en'       => $request->room_name_en,
            'rm_name_loc'             => $request->room_name_loc,
            'rm_max_pax' => $request->room_max_pax,
            'rm_phone_no'      => $request->room_phone_no,
            'rm_phone_ext'      => $request->room_phone_ext,
            'rm_key_code'      => $request->room_key_code,
            'rm_key_options'      => $request->room_key_options,
            'rm_connection'      => ($request->has('room_connection')) ? $request->room_connection : 0,
            'fo_status'      => $request->fo_status,
            'rm_active'      => ($request->has('room_active')) ? $request->room_active : 0,
            'hk_stauts'      => $request->hk_stauts,
            //'rm_active'      => $request->room_active,
            'sort_order'      => $this->Max_sortOrder() + 1,
            'room_type_id'      => $request->room_type_id,
            'floor_id'      => $request->floor_id,
            'view_id'      => $request->view_id,
            'room_statuse_id'      => (!empty($request->room_statuse_id)) ? $request->room_statuse_id : 1,
        ]);
        $rooms =  $this->roomModel::where('rm_name_en', $request->room_name_en)->with('room_type', 'floors')->get();
        return  $rooms;
    }

    public function storeMoreOneRoom($request)
    {
        DB::beginTransaction();
        try {
            foreach ($request as $room) {
                $roomRequest = new Request;
                $roomRequest->merge($room);
                $storeRoom = $this->store($roomRequest);
            }
            DB::commit();
            return 'rooms created';
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }
    }
    public function show($id)
    {

        $room = $this->roomModel::where('id', $id)->with('room_type', 'floors', 'ViewGardens')->first();
        return $room;
    }
    public function update($request, $id)
    {
        $periodicity_type = $request->periodicity_type;
        //    dd(empty($request['room_connection']));
        $room = $this->roomModel::where('id', $id)->first();
        if ($room) {
            $this->roomModel::where('id', $id)->Update([
                'rm_name_en'  => (!empty($request['room_name_en'])) ? $request['room_name_en'] : $room->rm_name_en,
                'rm_name_loc'  => (!empty($request['room_name_loc'])) ? $request['room_name_loc'] : $room->rm_name_loc,
                'rm_max_pax'  => (!empty($request['room_max_pax'])) ? $request['room_max_pax'] : $room->rm_max_pax,
                'rm_phone_no'  => (!empty($request['room_phone_no'])) ? $request['room_phone_no'] : $room->rm_phone_no,
                'rm_phone_ext'  => (!empty($request['room_phone_ext'])) ? $request['room_phone_ext'] : $room->rm_phone_ext,
                'rm_key_code'  => (!empty($request['room_key_code'])) ? $request['room_key_code'] : $room->rm_key_code,
                'rm_key_options'  => (!empty($request['room_key_options'])) ? $request['room_key_options'] : $room->rm_key_options,
                'rm_connection'  => ($request->has('room_connection')) ? $request['room_connection'] : $room->rm_connection,
                'fo_status'  => (!empty($request['fo_status'])) ? $request['fo_status'] : $room->fo_status,
                'rm_active'  => ($request->has('room_active')) ? $request['room_active'] : $room->rm_active,
                'hk_stauts'  => (!empty($request['hk_stauts'])) ? $request['hk_stauts'] : $room->hk_stauts,
                'sort_order'  => (!empty($request['sort_order'])) ? $request['sort_order'] : $room->sort_order,
                'room_type_id'  => (!empty($request['room_type_id'])) ? $request['room_type_id'] : $room->room_type_id,
                'floor_id'  => (!empty($request['floor_id'])) ? $request['floor_id'] : $room->floor_id,
                'room_statuse_id'  => (!empty($request['room_statuse_id'])) ? $request['room_statuse_id'] : $room->room_statuse_id,
                'view_id'  => ($request->has('view_id')) ? $request['view_id'] : $room->view_id,

            ]);
            $room = $this->roomModel::where('id', $id)->with('room_type', 'floors')->get();
            return  $room;
        } else {
            return null;
        }
    }
    public function destroy($id)
    {
        $room = $this->roomModel::find($id);
        if ($room) {
            $room->delete();
            return $room;
        } else {
            return null;
        }
    }
    public function geSoftDeletedData()
    {
        $roomsTrashed = $this->roomModel::onlyTrashed()->get();
        if ($roomsTrashed) {
            return $roomsTrashed;
        } else {
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

    public function getAllInhousRooms()
    {
        $inhousRoomData = $this->roomModel::where('fo_status', 'OC')
            ->with(['guests' => function ($q) {
                $q->where('is_checked_in', 1)
                    ->where('checked_out', 0)
                    ->with('window', 'profile');
            }])
            ->whereHas('guests', function ($q) {
                $q->where('is_checked_in', 1)
                    ->where('checked_out', 0)
                    ->with('window');
            })
            ->get();
        return  $inhousRoomData;
    }

    public function RoomStatus($request)
    {
        $respons = [];
        $roomsArr = [];
        $statusArr = [];
        $roomID = ($request->has('room_id') ? $request->room_id : null);
        $roomTypeID = ($request->has('roomTypeID') ? $request->roomTypeID : null);
        // $startDate = Carbon::parse($request->start_date);
        $startDate = $request->start_date;
        //$endDate = Carbon::parse($request->end_date);
        $endDate = $request->end_date;
        $allRooms = $this->generalInterface->getAllRoomsOrByIDOrRoomTypeID($roomID, $roomTypeID);
        if ($allRooms->first()) {
            foreach ($allRooms as $oneRoom) {

                $count = 0;
                $statusArr = [];
                $date = $startDate;
                // for($date = $startDate; $date->lte($endDate);$date->addDay())
                //  {
                for ($date = $startDate; $date <= $endDate; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
                    $checkRoomStatusInGuest = $this->generalInterface->checkRoomStatusInGuestForOneDay($date, $oneRoom->id);
                    // dump($checkRoomStatusInGuest);
                    if (!is_null($checkRoomStatusInGuest)) {
                        // dump($checkRoomStatusInGuest);
                        if ($count == 0) {
                            $roomseforMab = $oneRoom;
                        }
                        $count = 1;
                        $statusArr["$date"] = $checkRoomStatusInGuest;
                    } elseif (is_null($checkRoomStatusInGuest)) {
                        $checkRoomStatusInOoServices = $this->generalInterface->checkRoomStatusInOOservicesForOneDay($date, $oneRoom->id);
                        if (!is_null($checkRoomStatusInOoServices)) {
                            if ($count == 0) {
                                $roomseforMab = $oneRoom;
                            }
                            $count = 1;
                            $statusArr["$date"] = $checkRoomStatusInOoServices;

                            // return $checkRoomStatusInOoServices;
                        } else {
                            if ($count == 0) {
                                $roomseforMab = $oneRoom;
                            }
                            $count = 1;
                            $statusArr["$date"] = "VA";
                        }
                    } else {
                        if ($count == 0) {
                            $roomseforMab = $oneRoom;
                        }
                        $count = 1;
                        $statusArr["$date"] = "VA";
                    }
                }
                if (!is_null($roomseforMab)) {
                    $roomAfterAddStatus =  collect([$roomseforMab])->map(function ($item) use ($statusArr) {
                        $item->status = $statusArr;
                        return $item;
                    });
                }

                $roomseforMab = null;
                // $respons['rooms']=$roomAfterAddStatus;

                array_push($respons, $roomAfterAddStatus[0]);
            }

            return  $respons;
        }
    }

    public function getStatusByRoom($request)
    {
        $respons = [];
        $roomIDs = [];
        $roomsArr = [];
        $statusArr = [];
        $roomID = ($request->has('room_id') ? $request->room_id : null);
        $roomTypeID = ($request->has('roomTypeID') ? $request->roomTypeID : null);
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $allRooms = $this->generalInterface->getAllRoomsOrByIDOrRoomTypeID($roomID, $roomTypeID);
        // foreach($allRooms as $room)
        // {
        //     array_push($roomIDs,$room->id);
        // }
        if ($allRooms->first()) {
            // foreach($allRooms as $oneRoom)
            //  {
            $count = 0;
            $statusArr = [];
            $roomAfterAddStatus = [];
            for ($date = $startDate; $date <= $endDate; $date = date('Y-m-d', strtotime($date . ' +1 day'))) {
                //  dump($date);
                //return $allRooms[0];
                $checkRoomStatusInGuest = $this->generalInterface->getStatusByRoom($date, $allRooms);
                $respons["$date"] = $checkRoomStatusInGuest;
            }

            return $respons;
        }
    }

    public function copyRoomsFromFloorToAnother($request)
    {
        $RoomsToCopy = $this->roomModel->whereHas('floors', function ($q) use ($request) {
            $q->where('id', $request->FromFloor_id);
            return $q;
        })->get();
        foreach ($RoomsToCopy as $room) {
            $room->room_max_pax = $room->rm_max_pax;
            $room->room_connection = $room->rm_connection;
            $room->room_active = $room->rm_active;
            $room->floor_id = $request->toFloorID;
            $room->room_name_loc = substr_replace($room->rm_name_en, $request->newDegit, 0, $request->numberOfDegit);
            $room->room_name_en = $room->room_name_loc;
            $room->room_phone_no = $room->room_name_loc;
            $room->room_phone_ext = $room->room_name_loc;
            $room->room_key_code = $room->room_name_loc;
            $room->room_key_options = $room->room_name_loc;
            $room->fo_status = 'VA';
            $room->hk_stauts = 'CL';

            $room->room_connection = $room->rm_connection;
            $requestForStore = new Request();
            $requestForStore->merge($room->toArray());
            $roomAdded = $this->store($requestForStore);
        }
        $newRoomsData = $this->roomModel->where('floor_id', $request->toFloorID)->get();

        return $newRoomsData;
    }

    public function getAvalDummyRoom()
    {
        $avalDumyRomm = $this->roomModel->where('fo_status','VA')
        ->whereHas('room_type',function($q){
            $q->where('rm_type_code','DUM');
        })
        ->get();

        return $avalDumyRomm;
    }
}
