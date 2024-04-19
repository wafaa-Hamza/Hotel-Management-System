<?php

namespace App\Http\Controllers\Api\V1\Profiles\Calculates;

use App\helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DayCloseRecord;
use App\Repositoryinterface\Profiles\Calculate\RepositoryDayCloseRecordInterface;

class DayCloseRecordController extends Controller
{
    use helpers;

    private $dayCloseRecordInterface;
    public function __construct(RepositoryDayCloseRecordInterface $dayCloseRecordInterface)
    {
        $this->dayCloseRecordInterface = $dayCloseRecordInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $this->authorize('create-dayclose', DayCloseRecord::class);

 $store = $this->dayCloseRecordInterface->store($request);
 if($store == true)
 {
    return $this->apiResponse(['message' => 'Day Close Record Created successfully'],200);

 }else{
    return $this->apiResponse(['message' => 'Day Close Record Not Created'], 403);

 }
        

    }
    public function countFoStatus(Request $request)
    {
      
 $countRoomByFoStatus = $this->dayCloseRecordInterface->totalRoomByFoStatus($request);
        
 return $this->apiResponse(['data' => $countRoomByFoStatus],200);
    }

    public function getCheckInRooms(Request $request)
    {
        $checkedInRooms= $this->dayCloseRecordInterface->getCheckInRooms($request);
        return $this->apiResponse(['data' => $checkedInRooms],200);

    }
    public function getCheckOutRooms(Request $request)
    {
        $checkOutRooms = $this->dayCloseRecordInterface->getCheckOutRooms($request);
        return $this->apiResponse(['data' => $checkOutRooms],200);
    }
    public function getReservisionCancel(Request $request)
    {
        $reservationCancelCount = $this->dayCloseRecordInterface->getReservisionCancel($request);
        dd($reservationCancelCount);
    }
    public function getReservisionToday(Request $request)
    {
        $reservationToday = $this->dayCloseRecordInterface->getReservisionToday($request);
        dd($reservationToday);
    }
    public function guestLedger(Request $request)
    {
        $guestLedger = $this->dayCloseRecordInterface->guestLedger($request);
        dd($guestLedger);
    }

    public function getAllRoom()
    {
        $countOfRooms = $this->dayCloseRecordInterface->getAllRoom();
        dd($countOfRooms);
    }
    public function sumOfCheckInPax(Request $request)
    {
        $sumOfCheckInPax = $this->dayCloseRecordInterface->sumOfCheckInPax($request);
        dd($sumOfCheckInPax);
    }
    public function sumOfCheckOutPax(Request $request)
    {
        $sumOfCheckOutPax = $this->dayCloseRecordInterface->sumOfCheckOutPax($request);
        dd($sumOfCheckOutPax);
    }
    public function getCountOfGroup(Request $request)
    {
        $countOfGroub = $this->dayCloseRecordInterface->getCountOfGroup($request);
        dd($countOfGroub);
    }
    public function dayUseRoom(Request $request)
    {
        $countOfGroub = $this->dayCloseRecordInterface->dayUseRoom($request);
        dd($countOfGroub);
    }
    public function dayUseRoomCount(Request $request)
    {
        $dayUseRoomCount =$this->dayCloseRecordInterface->dayUseRoomCount($request);
        dd($dayUseRoomCount);
    }
    public function expectedtomorrowCount(Request $request)
    {
        $dayUseRoomCount =$this->dayCloseRecordInterface->expectedtomorrowCount($request);
        dd($dayUseRoomCount);
    }
    public function expectedtomorrowSumPax(Request $request)
    {
        $dayUseRoomCount =$this->dayCloseRecordInterface->expectedtomorrowSumPax($request);
        dd($dayUseRoomCount);
    }
    public function expectedOuttomorrowRoomsCount(Request $request)
    {
        $roomCount =$this->dayCloseRecordInterface->expectedOuttomorrowRoomsCount($request);
        dd($roomCount);
    }
    public function guestInhousPax(Request $request)
    {
        $guestInhousPax =$this->dayCloseRecordInterface->guestInhousPax($request);
        dd($guestInhousPax);
    }
    public function dayUseSumOfPax(Request $request)
    {
        $dayUseSumOfPax =$this->dayCloseRecordInterface->dayUseSumOfPax($request);
        dd($dayUseSumOfPax);
    }
    public function expectedOuttomorrowSumPax(Request $request)
    {
        $expectedOuttomorrowSumPax =$this->dayCloseRecordInterface->expectedOuttomorrowSumPax($request);
        dd($expectedOuttomorrowSumPax);
    }
    public function totalFbRateromeOthersIndrate(Request $request)
    {
        $totalFbRateromeOthersIndrate =$this->dayCloseRecordInterface->totalFbRateromeOthersIndrate($request,null);
        dd($totalFbRateromeOthersIndrate);
    }
    public function totalPaymants(Request $request)
    {
        $totalPaymants =$this->dayCloseRecordInterface->totalPaymants($request);
        dd($totalPaymants);
    }
    public function getCountOfCompOrHousRooms(Request $request)
    {
        $totalPaymants =$this->dayCloseRecordInterface->getCountOfCompOrHousRooms($request,null);
        dd($totalPaymants);
    }
    public function Calc_Of_AR_Deposit_Payment()
    {
        $calc_payment =$this->dayCloseRecordInterface->Calc_Of_AR_Deposit_Payment();
        dd($calc_payment);
    }
    public function Calc_Of_AR_Balance()
    {
        $calc_ARbalance =$this->dayCloseRecordInterface->Calc_Of_AR_Balance();
        dd($calc_ARbalance);
    }
    public function Count_Of_Extended_Rooms(Request $request)
    {
        $countExtendRoom =$this->dayCloseRecordInterface->Count_Of_Extended_Rooms($request);
        dd($countExtendRoom);
    }
    public function Sum_Of_Extended_Pax(Request $request)
    {
        $sumExtendPax =$this->dayCloseRecordInterface->Sum_Of_Extended_Pax($request);
        dd($sumExtendPax);
    }
    public function Sum_Of_Total_Taxes(Request $request)
    {
        $sumTaxes =$this->dayCloseRecordInterface->Sum_Of_Total_Taxes($request);
        dd($sumTaxes);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
