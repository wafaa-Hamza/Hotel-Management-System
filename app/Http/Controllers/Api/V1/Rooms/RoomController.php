<?php

namespace App\Http\Controllers\Api\V1\Rooms;

use App\helpers;
use App\Models\Room;
use App\Models\User;
use App\Models\Floor;
use App\Models\Tower;
use App\Models\Guests;
use App\Models\RoomStatus;
use App\Models\OordService;

use Illuminate\Http\Request;
use App\Http\Requests\RoomRequest;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Controller;
use App\Http\Requests\CopyRoomRequest;
use function PHPUnit\Framework\isEmpty;
use App\Http\Requests\RoomStatusRequest;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\Rooms\RoomResource;
use App\Http\Requests\storeMoreOneRoomRequest;
use App\Http\Resources\Rooms\InhousRoomsResource;
use App\Repositoryinterface\Rooms\RepositoryRoomInterface;

class RoomController extends Controller
{
    use helpers;

    private $RepositoryRoomInterface;

    public function __construct(RepositoryRoomInterface $RepositoryRoomInterface)
    {
        $this->RepositoryRoomInterface = $RepositoryRoomInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        //      $this->authorize('view-room', Room::class);

        $rooms = $this->RepositoryRoomInterface->index();
        if ($rooms->first()) {
            return $this->apiResponse(new GeneralCollection($rooms, RoomResource::class), 200);
        } else
            return $this->apiResponse(["message" => __("not found")], 200);
    }
    public function roomPagination()
    {
        //        $this->authorize('view-room', Room::class);
        $rooms = $this->RepositoryRoomInterface->roomPagination();
        if ($rooms->first()) {
            return $this->apiResponse(new GeneralCollection($rooms, RoomResource::class), 200);
        } else
            return $this->apiResponse(["message" => __("not found")], 404);
    }

    public function getAllInhousRooms()
    {
        //        $this->authorize('view-room', Room::class);
        $inhousRooms = $this->RepositoryRoomInterface->getAllInhousRooms();

        if ($inhousRooms->first()) {
            // return $this->apiResponse(new GeneralCollection($inhousRooms, RoomResource::class), 200);
            return $this->apiResponse(new GeneralCollection($inhousRooms, InhousRoomsResource::class), 200);
        } else
            return $this->apiResponse(["message" => __("not found")], 404);
    }

    public function roomsFilter(Request $request)
    {

        if ($request->room_type_id != null) {
            $rooms = Room::with('room_type', 'floors')->where('room_type_id', $request->room_type_id)->get();
        } else {
            $rooms = Room::with('room_type', 'floors')->get();
        }
        return response()->json($rooms);
    }
    public function roomRack(Request $request)
    {



        if ($request->room_type_id != null) 
        {
            $floors = Floor::with(
                ['rooms' => function ($q) use ($request) {
                    $q->where('rm_max_pax', '>', 0);
                    $q->where('room_type_id', $request->room_type_id);
                    $q->with(['guests' => function ($q2) use ($request) {
                        $q2->where('is_checked_in', 1);
                        $q2->where('checked_out', 0);
                        $q2->with('profile');
                    }]);
                    $q->with(['reservation' => function ($q2) use ($request) {
                        $q2->where('is_checked_in', 0);
                        $q2->where('checked_out', 0);
                        $q2->where('is_cancel', 0);
                        $q2->whereIn('res_status', ['CNF', 'NCF']);
                        $q2->with('profile');
                    }]);
                }]
            )->get();
        } else {

            $floors = Floor::with(
                ['rooms' => function ($q) use ($request) {
                    $q->where('rm_max_pax', '>', 0);
                    $q->with(['guests' => function ($q2) use ($request) {
                        $q2->where('is_checked_in', 1);
                        $q2->where('checked_out', 0);
                        $q2->with('profile');
                    }]);
                    $q->with(['reservation' => function ($q2) use ($request) {
                        $q2->where('is_checked_in', 0);
                        $q2->where('checked_out', 0);
                        $q2->where('is_cancel', 0);
                        $q2->whereIn('res_status', ['CNF', 'NCF']);
                        $q2->with('profile');
                    }]);
                }]
            )->get();
        }



        $rack_arr =  [];
        $floor_arr =  [];
        $vacantCleanCount = 0;
        $vacantDirtyCount = 0;
        $BlockedCleanCount = 0;
        $BlockedDirtyCount = 0;
        $BlockedOccupiedCount = 0;
        $OccupiedCleanCount = 0;
        $OccupiedDirtyCount = 0;
        $DueOutCount = 0;
        $Out_of_OrderCount = 0;
        $Out_of_ServiceCount = 0;

        $vacantCleanArr = [];
        $vacantDirtyArr = [];
        $BlockedCleanArr = [];
        $BlockedDirtyArr = [];
        $BlockedOccupiedArr = [];
        $OccupiedCleanArr = [];
        $OccupiedDirtyArr = [];
        $DueOutArr = [];
        $Out_of_OrderArr = [];
        $Out_of_ServiceArr = [];

        $vacantClean = 'VACL';
        $vacantDirty = 'VADI';
        $BlockedClean = 'BLCL';
        $BlockedDirty = 'BLDI';
        $BlockedOccupied = 'BLOC';
        $OccupiedClean = 'OCCL';
        $OccupiedDirty = 'OCDI';
        $DueOut = 'DO';
        $Out_of_Order = 'OO';
        $Out_of_Service = 'OS';

        $color_status = RoomStatus::all();
        foreach ($floors as $floor) {
            $rooms = $floor->rooms;



            foreach ($rooms as $room) {

                $guest_inHouse = [];
                $reservation   = [];
                $rack2 = [];


                $foStatus = $room->fo_status;
                $hkStatus = $room->hk_status;

                $room_id = $room->id;

                $roomName       = $room->rm_name_en;
                $roomtype_id       = $room->room_type_id;
                $roomName_Loc   = $room->rm_name_loc;
                $room_type = $room->room_type;

                if ($foStatus == "OO" || $foStatus == "OS") {
                    $status         = $room->fo_status;
                } else {
                    $status         = $room->fo_status . $room->hk_stauts;
                }


                if ($vacantClean == $status) {
                    $vacantCleanCount = $vacantCleanCount + 1;
                } elseif ($vacantDirty == $status) {
                    $vacantDirtyCount = $vacantDirtyCount + 1;
                } elseif ($BlockedClean == $status) {
                    $BlockedCleanCount = $BlockedCleanCount + 1;
                } elseif ($BlockedDirty == $status) {
                    $BlockedDirtyCount = $BlockedDirtyCount + 1;
                } elseif ($BlockedOccupied == $status) {
                    $BlockedOccupiedCount = $BlockedOccupiedCount + 1;
                } elseif ($OccupiedClean == $status) {
                    $OccupiedCleanCount = $OccupiedCleanCount + 1;
                } elseif ($OccupiedDirty == $status) {
                    $OccupiedDirtyCount = $OccupiedDirtyCount + 1;
                } elseif ($DueOut == $status) {
                    $DueOutCount = $DueOutCount + 1;
                } elseif ($Out_of_Order == $status) {
                    $Out_of_OrderCount = $Out_of_OrderCount + 1;
                } elseif ($Out_of_Service == $status) {
                    $Out_of_ServiceCount = $Out_of_ServiceCount + 1;
                }


                if ($room->fo_status == "OC") {
                    $guest_inHouse = $room->guests;
                } elseif ($room->fo_status == "BL") {
                    $reservation =  $room->reservation;
                } elseif ($room->fo_status == "OO") {
                    $order = OordService::where('room_id', $room->id)->where("is_return", 0)->first();

                    if ($order) {
                        $end_date  = $order->end_date;
                        $notes     = $order->notes;

                        $rack2 = [
                            'end_date'       => $end_date,
                            'notes'         => $notes,
                        ];
                    }
                }
                $status_data = [];
                foreach ($color_status as $stat) {
                    if ($stat->status_code == $status) {
                        $status_data = $stat;
                    }
                }
                $color = $status_data->color;

                $rack = [

                    'room_id' =>  $room_id,

                    'roomName'       => $roomName,
                    'roomName_Loc'   => $roomName_Loc,
                    'status'         => $status,
                    'room_type_id'   => $roomtype_id,
                    'guest_details'  => $guest_inHouse,
                    'reservation'    => $reservation,
                    'Ord_status'     => $rack2,
                    'color_status'   => $color,
                    'status_data'    => $status_data,
                    'room_type'      => $room_type

                ];
                array_push($rack_arr, $rack);
            }

            $status_count_arr = [
                $vacantClean     => $vacantCleanCount,
                $vacantDirty     => $vacantDirtyCount,
                $BlockedClean    => $BlockedCleanCount,
                $BlockedDirty    => $BlockedDirtyCount,
                $BlockedOccupied => $BlockedOccupiedCount,
                $OccupiedClean   => $OccupiedCleanCount,
                $OccupiedDirty   => $OccupiedDirtyCount,
                $DueOut          => $DueOutCount,
                $Out_of_Order    => $Out_of_OrderCount,
                $Out_of_Service  => $Out_of_ServiceCount,

            ];



            $floorArray = [
                'floor_id' => $floor->id,
                'floor_name' => $floor->floor_name,
                'floor_name_loc' => $floor->floor_name_loc,
                'rooms' => $rack_arr,




            ];
            array_push($floor_arr, $floorArray);
            $rack_arr = [];
            $floorArray = [];
        }
        //dd($color_status);
        $status_arr = [];
        foreach ($color_status as $state) {
            // dump($state->status_code);
            if ($state->status_code == 'VACL') {
                $vacantCleanArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $vacantCleanCount
                ];
                array_push($status_arr, $vacantCleanArr);
            } elseif ($state->status_code == 'VADI') {
                $vacantDirtyArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $vacantDirtyCount
                ];
                array_push($status_arr,  $vacantDirtyArr);
            } elseif ($state->status_code == 'BLCL') {
                $BlockedCleanArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $BlockedCleanCount
                ];
                array_push($status_arr,   $BlockedCleanArr);
            } elseif ($state->status_code == 'BLDI') {
                $BlockedDirtyArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $BlockedDirtyCount
                ];
                array_push($status_arr, $BlockedDirtyArr);
            } elseif ($state->status_code == 'BLOC') {
                $BlockedOccupiedArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $BlockedOccupiedCount
                ];
                array_push($status_arr, $BlockedOccupiedArr);
            } elseif ($state->status_code == 'OCCL') {
                $OccupiedCleanArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $OccupiedCleanCount
                ];
                array_push($status_arr, $OccupiedCleanArr);
            } elseif ($state->status_code == 'OCDI') {
                $OccupiedDirtyArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $OccupiedDirtyCount
                ];
                array_push($status_arr, $OccupiedDirtyArr);
            } elseif ($state->status_code == 'DO') {
                $DueOutArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $DueOutCount
                ];
                array_push($status_arr,  $DueOutArr);
            } elseif ($state->status_code == 'OO') {
                $Out_of_OrderArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' =>  $Out_of_OrderCount
                ];
                array_push($status_arr, $Out_of_OrderArr);
            } elseif ($state->status_code == 'OS') {
                $Out_of_ServiceArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' =>   $Out_of_ServiceCount
                ];
                array_push($status_arr, $Out_of_ServiceArr);
            }
        }
        return response()->json([
            "message" => ' Details Of Our Rooms',
            'data' => [
                'floorData' => $floor_arr,
                'status' => $status_arr
            ]
        ]);
    }
    public function towerRoomRack(Request $request)
    {

        if ($request->room_type_id != null) 
        {
            $towers = Tower::with(
                ['floor' => function($q) use ($request){
                    $q->with(
                        ['rooms' => function ($q) use ($request) {
                            $q->where('rm_max_pax', '>', 0);
                            $q->where('room_type_id', $request->room_type_id);
                            $q->with(['guests' => function ($q2) use ($request) {
                                $q2->where('is_checked_in', 1);
                                $q2->where('checked_out', 0);
                                $q2->with('profile');
                            }]);
                            $q->with(['reservation' => function ($q2) use ($request) {
                                $q2->where('is_checked_in', 0);
                                $q2->where('checked_out', 0);
                                $q2->where('is_cancel', 0);
                                $q2->whereIn('res_status', ['CNF', 'NCF']);
                                $q2->with('profile');
                            }]);
                        }]
                    );
                }]
               
            )->get();
        } else {

            $towers = Tower::with(
                ['floor' => function($q) use ($request){
                    $q->with( 
                         ['rooms' => function ($q) use ($request) {
                        $q->where('rm_max_pax', '>', 0);
                        $q->with(['guests' => function ($q2) use ($request) {
                            $q2->where('is_checked_in', 1);
                            $q2->where('checked_out', 0);
                            $q2->with('profile');
                        }]);
                        $q->with(['reservation' => function ($q2) use ($request) {
                            $q2->where('is_checked_in', 0);
                            $q2->where('checked_out', 0);
                            $q2->where('is_cancel', 0);
                            $q2->whereIn('res_status', ['CNF', 'NCF']);
                            $q2->with('profile');
                        }]);
                    }]
                );
                }]
              
            )->get();

        }



        $rack_arr =  [];
        $floor_arr =  [];
        $tower_arr =  [];
        $vacantCleanCount = 0;
        $vacantDirtyCount = 0;
        $BlockedCleanCount = 0;
        $BlockedDirtyCount = 0;
        $BlockedOccupiedCount = 0;
        $OccupiedCleanCount = 0;
        $OccupiedDirtyCount = 0;
        $DueOutCount = 0;
        $Out_of_OrderCount = 0;
        $Out_of_ServiceCount = 0;

        $vacantCleanArr = [];
        $vacantDirtyArr = [];
        $BlockedCleanArr = [];
        $BlockedDirtyArr = [];
        $BlockedOccupiedArr = [];
        $OccupiedCleanArr = [];
        $OccupiedDirtyArr = [];
        $DueOutArr = [];
        $Out_of_OrderArr = [];
        $Out_of_ServiceArr = [];

        $vacantClean = 'VACL';
        $vacantDirty = 'VADI';
        $BlockedClean = 'BLCL';
        $BlockedDirty = 'BLDI';
        $BlockedOccupied = 'BLOC';
        $OccupiedClean = 'OCCL';
        $OccupiedDirty = 'OCDI';
        $DueOut = 'DO';
        $Out_of_Order = 'OO';
        $Out_of_Service = 'OS';

        $color_status = RoomStatus::all();
        foreach ($towers as $tower) {
            foreach( $tower->floor as $floor)
            {
                foreach ($floor->rooms as $room) 
                {

                    $guest_inHouse = [];
                    $reservation   = [];
                    $rack2 = [];
    
    
                    $foStatus = $room->fo_status;
                    $hkStatus = $room->hk_status;
    
                    $room_id = $room->id;
    
                    $roomName       = $room->rm_name_en;
                    $roomtype_id       = $room->room_type_id;
                    $roomName_Loc   = $room->rm_name_loc;
                    $room_type = $room->room_type;
    
                    if ($foStatus == "OO" || $foStatus == "OS") {
                        $status         = $room->fo_status;
                    } else {
                        $status         = $room->fo_status . $room->hk_stauts;
                    }
    
    
                    if ($vacantClean == $status) {
                        $vacantCleanCount = $vacantCleanCount + 1;
                    } elseif ($vacantDirty == $status) {
                        $vacantDirtyCount = $vacantDirtyCount + 1;
                    } elseif ($BlockedClean == $status) {
                        $BlockedCleanCount = $BlockedCleanCount + 1;
                    } elseif ($BlockedDirty == $status) {
                        $BlockedDirtyCount = $BlockedDirtyCount + 1;
                    } elseif ($BlockedOccupied == $status) {
                        $BlockedOccupiedCount = $BlockedOccupiedCount + 1;
                    } elseif ($OccupiedClean == $status) {
                        $OccupiedCleanCount = $OccupiedCleanCount + 1;
                    } elseif ($OccupiedDirty == $status) {
                        $OccupiedDirtyCount = $OccupiedDirtyCount + 1;
                    } elseif ($DueOut == $status) {
                        $DueOutCount = $DueOutCount + 1;
                    } elseif ($Out_of_Order == $status) {
                        $Out_of_OrderCount = $Out_of_OrderCount + 1;
                    } elseif ($Out_of_Service == $status) {
                        $Out_of_ServiceCount = $Out_of_ServiceCount + 1;
                    }
    
    
                    if ($room->fo_status == "OC") {
                        $guest_inHouse = $room->guests;
                    } elseif ($room->fo_status == "BL") {
                        $reservation =  $room->reservation;
                    } elseif ($room->fo_status == "OO") {
                        $order = OordService::where('room_id', $room->id)->where("is_return", 0)->first();
    
                        if ($order) {
                            $end_date  = $order->end_date;
                            $notes     = $order->notes;
    
                            $rack2 = [
                                'end_date'       => $end_date,
                                'notes'         => $notes,
                            ];
                        }
                    }
                    $status_data = [];
                    foreach ($color_status as $stat) {
                        if ($stat->status_code == $status) {
                            $status_data = $stat;
                        }
                    }
                    $color = $status_data->color;
    
                    $rack = [
    
                        'room_id' =>  $room_id,
    
                        'roomName'       => $roomName,
                        'roomName_Loc'   => $roomName_Loc,
                        'status'         => $status,
                        'room_type_id'   => $roomtype_id,
                        'guest_details'  => $guest_inHouse,
                        'reservation'    => $reservation,
                        'Ord_status'     => $rack2,
                        'color_status'   => $color,
                        'status_data'    => $status_data,
                        'room_type'      => $room_type
    
                    ];
                    array_push($rack_arr, $rack);
                }

                $status_count_arr = [
                    $vacantClean     => $vacantCleanCount,
                    $vacantDirty     => $vacantDirtyCount,
                    $BlockedClean    => $BlockedCleanCount,
                    $BlockedDirty    => $BlockedDirtyCount,
                    $BlockedOccupied => $BlockedOccupiedCount,
                    $OccupiedClean   => $OccupiedCleanCount,
                    $OccupiedDirty   => $OccupiedDirtyCount,
                    $DueOut          => $DueOutCount,
                    $Out_of_Order    => $Out_of_OrderCount,
                    $Out_of_Service  => $Out_of_ServiceCount,
    
                ];

                $floorArray = [
                    'floor_id' => $floor->id,
                    'floor_name' => $floor->floor_name,
                    'floor_name_loc' => $floor->floor_name_loc,
                    'rooms' => $rack_arr,
    
    
    
    
                ];

                array_push($floor_arr, $floorArray);
                $rack_arr = [];
                $floorArray = [];
            }


            $towerArray = [
                'tower_id' => $tower->id,
                'tower_name' => $tower->name,
                'tower_name_loc' => $tower->name_loc,
                'flooers' => $floor_arr,
            ];

            array_push($tower_arr, $towerArray);
            $floor_arr = [];
            $towerArray = [];

        }
          
        $status_arr = [];
        foreach ($color_status as $state) {
            // dump($state->status_code);
            if ($state->status_code == 'VACL') {
                $vacantCleanArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $vacantCleanCount
                ];
                array_push($status_arr, $vacantCleanArr);
            } elseif ($state->status_code == 'VADI') {
                $vacantDirtyArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $vacantDirtyCount
                ];
                array_push($status_arr,  $vacantDirtyArr);
            } elseif ($state->status_code == 'BLCL') {
                $BlockedCleanArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $BlockedCleanCount
                ];
                array_push($status_arr,   $BlockedCleanArr);
            } elseif ($state->status_code == 'BLDI') {
                $BlockedDirtyArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $BlockedDirtyCount
                ];
                array_push($status_arr, $BlockedDirtyArr);
            } elseif ($state->status_code == 'BLOC') {
                $BlockedOccupiedArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $BlockedOccupiedCount
                ];
                array_push($status_arr, $BlockedOccupiedArr);
            } elseif ($state->status_code == 'OCCL') {
                $OccupiedCleanArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $OccupiedCleanCount
                ];
                array_push($status_arr, $OccupiedCleanArr);
            } elseif ($state->status_code == 'OCDI') {
                $OccupiedDirtyArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $OccupiedDirtyCount
                ];
                array_push($status_arr, $OccupiedDirtyArr);
            } elseif ($state->status_code == 'DO') {
                $DueOutArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' => $DueOutCount
                ];
                array_push($status_arr,  $DueOutArr);
            } elseif ($state->status_code == 'OO') {
                $Out_of_OrderArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' =>  $Out_of_OrderCount
                ];
                array_push($status_arr, $Out_of_OrderArr);
            } elseif ($state->status_code == 'OS') {
                $Out_of_ServiceArr = [
                    'code' => $state->status_code,
                    'name' => $state->name,
                    'name_loc' => $state->name_loc,
                    'color' => $state->color,
                    'count' =>   $Out_of_ServiceCount
                ];
                array_push($status_arr, $Out_of_ServiceArr);
            }
        }
        return response()->json([
            "message" => ' Details Of Our Rooms',
            'data' => [
                'towers' => $tower_arr,
                'status' => $status_arr
            ]
        ]);
    }

    public function Rearrange_sort_order()
    {

        $rooms = Room::orderBy('floor_id')->orderBy('rm_name_en')
            ->get();

        foreach ($rooms as $index => $room) {

            Room::where('rm_name_en', $room->rm_name_en)
                ->update(['sort_order' => $index + 1]);
        }
        return Room::orderBy('sort_order')->get();
    }


    public function store(RoomRequest $request)
    {

        $rooms = $this->RepositoryRoomInterface->store($request);

        return $this->apiResponse(new GeneralCollection($rooms, RoomResource::class), 201);
    }
    public function storeMoreOneRoom(storeMoreOneRoomRequest $request)
    {
        // dd($request);
        $rooms = $this->RepositoryRoomInterface->storeMoreOneRoom($request->rooms);

        return $this->apiResponse(['message', $rooms]);
    }

    public function show($id)
    {
        //        $this->authorize('view-room', Room::class);
        $room = $this->RepositoryRoomInterface->show($id);

        if ($room) {
            return $this->apiResponse(['data' => collect(new RoomResource($room))], 200);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    public function update(RoomRequest $request, $id)
    {
        //  dd($request);

        $room = $this->RepositoryRoomInterface->update($request, $id);
        if ($room) {

            return $this->apiResponse(new GeneralCollection($room, RoomResource::class), 200);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //        $this->authorize('delete-room', Room::class);
        $room = $this->RepositoryRoomInterface->destroy($id);

        if ($room) {
            return $this->apiResponse(["message" => __("the Room Has Been Deleted")], 200);
        } else {
            return $this->apiResponse(['message' => __("not found")], 404);
        }
    }

    public function geSoftDeletedData()
    {
        //        $this->authorize('view-deletedroom', Room::class);
        $roomsTrashed = $this->RepositoryRoomInterface->geSoftDeletedData();

        if ($roomsTrashed->first()) {
            return $this->apiResponse(new GeneralCollection($roomsTrashed, RoomResource::class), 200);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    public function restorDataTrashed($id)
    {
        //        $this->authorize('restore-deletedroom', Room::class);
        $roomRestore = $this->RepositoryRoomInterface->restorDataTrashed($id);

        if ($roomRestore->first()) {
            return $this->apiResponse(new GeneralCollection($roomRestore, RoomResource::class), 200);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    public function roomStatus(RoomStatusRequest $request)
    {
        //        $this->authorize('view-room', Room::class);
        $roomStatus = $this->RepositoryRoomInterface->getStatusByRoom($request);

        // return $this->apiResponse(['data'=>$roomStatus]);
        return $this->apiResponse($roomStatus);
        //  return response()->json([collect([$roomStatus])->first()]);
    }

    /**
     * Undocumented function
     *
     * @param [type] $request
     * {fromFloor_id,toFloorID,newDegit,numberOfDegit}
     * @return void
     */
    public function copyRoomsFromFloorToAnother(CopyRoomRequest $request)
    {
        $room = $this->RepositoryRoomInterface->copyRoomsFromFloorToAnother($request);

        if ($room) {
            return $this->apiResponse(new GeneralCollection($room, RoomResource::class), 200);
        } else {
            return $this->apiResponse(["message" => __("not found")]);
        }
    }
    public function getAvalDummyRoom()
    {
        $room = $this->RepositoryRoomInterface->getAvalDummyRoom();

        if (!empty($room->first())) {
            return $this->apiResponse(new GeneralCollection($room, RoomResource::class), 200);
        } else {
            return $this->apiResponse(["message" => __("not found")]);
        }
    }
}
