<?php

namespace App\Repository\profiles\guestProfile;

use App\helpers;
use Carbon\Carbon;
use App\Models\Room;
use App\Models\Guests;
use App\Models\RoomType;
use App\Models\OordService;
use Illuminate\Http\Request;
use App\Models\guest_profile;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestInhouseInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryReservisionChartSummaryInterface;


class DBrepositoryReservisionChartSummary implements RepositoryReservisionChartSummaryInterface
{

    use helpers;
    private $guestModel;
    private $roomModel;
    private $guestInhouseInterface;
    private $OordServices;
    private $roomTypeModel;
    public function __construct(Guests $guestModel, Room $roomModel,RepositoryGuestInhouseInterface $guestInhouseInterface,OordService $OordServices,
    RoomType $roomTypeModel)
     {
        $this->guestModel = $guestModel;
        $this->roomModel = $roomModel;
        $this->roomTypeModel = $roomTypeModel;
        $this->OordServices = $OordServices;
        $this->guestInhouseInterface = $guestInhouseInterface;
    }

    public function allRoom()
    {
        $allRoom = $this->roomModel->where('rm_max_pax','>',0)->where('rm_active',1)->count();
        return $allRoom;
    }
    public function expectedArrivalInDate($date)
    {
        $expectedArrival = $this->guestModel->whereDate('in_date', $date)->where('is_checked_in',0)->count();
        return  $expectedArrival;
    }
    public function availableRoomsInDate($date)
    {
        // $request = new Request;
        // $arr = [
        //     'in_date' => $date,
        //     'out_date' => $date,
        // ];
        // $request->merge($arr);
        //$this->guestInhouseInterface->availability($request);
       // $roomCount =[];
       // $notAvailableRoom =[];
       
       $countRoomsOord = $this->OORomms($date)+$this->OSRooms($date);
        // foreach ($countRoomsOord as $romNotAvailable) {
        //     if ($romNotAvailable->room_id != null) {
        //         array_push($notAvailableRoom, $romNotAvailable->room_id);
        //     }
        // }
        //******************check in guest reservission and inhous */
        $totCountRoom  = $this->allRoom();
        //dd($roomType->id);
        $notAvelableGuest =  $this->guestModel::
        where(function ($q) use ($date) {
         $q->whereDate('in_date', '<=', $date)->whereDate('out_date', '>=', $date);
        })->Where('is_reservation', 1)->Where(function ($query) {
            $query->Where('res_status', 'CNF');
            $query->orWhere('res_status', 'NCF');
        })->Where('is_cancel', 0)->Where('res_status', '!=', 'CNC')->Where('checked_out', 0)

        ->orWhere(function ($query) use ($date) {
                    $query->whereDate('in_date', '<=', $date)->whereDate('out_date', '>=', $date);
                $query->where('is_checked_in', 1);
                $query->where('is_reservation', 0);
                $query->where('checked_out', 0);
            })->count();
  
        //************************************************************************************************************************* */

        // foreach ($notAvelableGuest as $romNotAvailable) {
        //     if (!in_array($romNotAvailable->room_id, $notAvailableRoom)) {
        //         if ($romNotAvailable->room_id != null) {
        //             array_push($notAvailableRoom, $romNotAvailable->room_id);
        //         }
        //     }
        // }

        if ($countRoomsOord) {
            
            $roomCount = ($totCountRoom - $notAvelableGuest - $countRoomsOord);
        } else {
            $roomCount =  ($totCountRoom - $notAvelableGuest);
        }

        return $roomCount;
    }
    public function avrageRoomRate($date)
    {
        $RoomRatecountAndSumRmRate = $this->guestModel
            ->where('in_date', '<=', $date)
            ->where('out_date', '>=', $date)
            ->where('is_cancel', 0)
            ->where('checked_out', 0)
            ->selectRaw('SUM(rm_rate) as total_rm_rate, COUNT(*) as count')
            ->first();
 
        if ($RoomRatecountAndSumRmRate->count > 0) {
            $avgRoomRate = $RoomRatecountAndSumRmRate->total_rm_rate / $RoomRatecountAndSumRmRate->count;
        } else {
            $avgRoomRate = 0; // Handle division by zero or no matching records
        }

        return  $avgRoomRate;

    }
    public function expectedOut($date)
    {
        $expectedOut = $this->guestModel->whereDate('out_date', $date)->where('checked_out',0)->where('is_cancel', 0)->count();
        return $expectedOut;
    }
    public function totalGrt($date)
    {
        $totalGrtCount = $this->guestModel->where('in_date','<=',$date)->where('out_date','>=',$date)
        ->where('is_checked_in',0)
         ->where('is_reservation',1)->where('checked_out',0)->where('res_status','CNF')->count();
         return $totalGrtCount;
    }
    public function totalNotCon($date)
    {
        $totalNotConCount = $this->guestModel->where('in_date','<=',$date)->where('out_date','>=',$date)
        ->where('is_checked_in',0)
        ->where('is_reservation',1)->where('checked_out',0)->where('res_status','NCF')->count();
        return $totalNotConCount;
    }
    public function OCRooms($date)
    {
        $ocRoomCount = $this->guestModel->where('in_date', '<=', $date)->where('is_cancel', 0)->where('checked_out', 0)
                ->Where('out_date', '>=', $date)
                ->count();

                return $ocRoomCount;
    }
    public function OORomms($date)
    {
        $totalUnavailableRooms = OordService::whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=',$date)
            ->where('is_return', 0)->where('oord_type' , 'OO')->count();
            return $totalUnavailableRooms;
    }
    public function OSRooms($date)
    {
        $totalUnavailableRooms = $this->OordServices::whereDate('start_date', '<=', $date)
        ->whereDate('end_date', '>=', $date)
        ->where('is_return', 0)->where('oord_type' , 'OS')->count();

        return $totalUnavailableRooms;
    }
    public function netRooms($date)
    {
       $netRooms = $this->allRoom() - $this->OORomms($date) - $this-> OSRooms($date);
       return  $netRooms;
    }
    public function reservedRooms($date)
    {
         $reservedRooms = $this->guestModel->where('in_date','<=',$date)->where('out_Date','>=',$date)
         ->where('is_checked_in', false) ->where('is_reservation',1)->where('checked_out',0)->where('is_cancel', 0)->count();
         return $reservedRooms;
    }
    public function roomType()
    {
        $allRoomTypes = $this->roomTypeModel->where('rm_type_rentable',1)
        ->where('def_pax','>',0)->select('id','rm_type_name','rm_type_name_loc')->get();
        return  $allRoomTypes;
    }

    public function getCountOfRoomType($date)
    {
        $roomTypeNameAndCount =[];
        foreach($this->roomType() as $roomType)
        {
            $countGuestOfRoomType = $this->guestModel->where('in_date','<=',$date)->where('out_date','>=',$date)->where('is_cancel', 0)->where('checked_out',0)->where('room_type_id',$roomType->id)
            ->count();

            $roomTypeNameAndCount["$roomType->rm_type_name"] = $countGuestOfRoomType;
        }

        return $roomTypeNameAndCount;
    }

    public function reservisionChartSummary($request)
    {
        $result = [];
        $dateData = [];
        $dataFprDateArr =[];
        $result['allRooms'] = $this->allRoom();
        $result['roomTypes'] = $this->roomType();
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $dateKey = $date->format('Y-m-d');
            $dataFoDate = [
                'date' => $dateKey,
                'expectedArrivalInDate' => $this->expectedArrivalInDate($date),
                'availableRoomsInDate' => $this->availableRoomsInDate($date),
                'avrageRoomRate' => $this->avrageRoomRate($date),
                'expectedOut' => $this->expectedOut($date),
                'totalGrt' => $this->totalGrt($date),
                'totalNotCon' => $this->totalNotCon($date),
                'OCRooms' => $this->OCRooms($date),
                'OORomms' => $this->OORomms($date),
                'OSRooms' => $this->OSRooms($date),
                'netRooms' => $this->netRooms($date),
                'reservedRooms' => $this->reservedRooms($date),
                'getCountOfRoomType' => $this->getCountOfRoomType($date),

            ];
            array_push($dataFprDateArr, $dataFoDate);
        }
        $result['dataForDate'] = $dataFprDateArr;


        return $result;
    }
}
