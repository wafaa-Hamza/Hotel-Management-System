<?php
namespace App\Repositoryinterface;

interface Generalinterface{
    public function total_roomCharge($request);
    public function total_fb_charge($request);
    public function total_other_charge($request);
    public function total_payment($request);

    public function guestBalance($request);
    public function taxes ($request);
    public function total ($request);
    
    public function getAllRoomsOrByIDOrRoomTypeID($roomID,$RoomTypeID);
    public function checkRoomStatusInGuestForOneDay($request,$roomID);
    public function checkRoomStatusInOOservicesForOneDay($request,$roomID);
    public function getStatusByRoom($date,$roomID);






   
}