<?php
namespace App\Repositoryinterface\Profiles\Calculate;
interface RepositoryDayCloseRecordInterface{

    public function totalRoomByFoStatus($FoStatus);
    public function getCheckInRooms($date);
    public function getCheckOutRooms($date);
    public function getReservisionCancel($date);
    public function getReservisionToday($date);
    public function guestLedger($data);

    public function getAllRoom();

    public function sumOfCheckInPax($date);
    public function sumOfCheckOutPax($date);
    public function getCountOfGroup($date);
    public function dayUseRoom($date);
    public function dayUseRoomCount($date);
    public function expectedtomorrowCount($date);
    public function expectedtomorrowSumPax($date);
    public function expectedOuttomorrowRoomsCount($date);
    public function guestInhousPax($date);
    public function dayUseSumOfPax($date);
    public function expectedOuttomorrowSumPax($date,);
    public function guestNoOfVIP($date);

    public function totalFbRateromeOthersIndrate($data, $ledgerID);
    public function totalPaymants($date);
    //start wafa
    public function getCountOfCompOrHousRooms($way_of_payment);
    public function Calc_Of_AR_Deposit_Payment();
    public function Calc_Of_AR_Balance();
    public function Count_Of_Extended_Rooms($date);
    public function Sum_Of_Extended_Pax($date);
    public function Sum_Of_Total_Taxes($date);
    //end wafa
    public function store($date);


    

}