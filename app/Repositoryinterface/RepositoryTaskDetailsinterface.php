<?php
namespace App\Repositoryinterface;

interface RepositoryTaskDetailsinterface{
    // public function index();
    public function store($request);
    public function dueOut($hotelData);
    public function paymentRequired();
    public function reservitionNotBlock($hotelData);
    public function reservitionNotConferm($hotelData);
    public function OOandOSREturn($hotelData);
    public function cleanRooms();
   // public function show($id);
    public function update($request, $id);
        public function lateTime($expected_finish, $actual_finish);
            public function paymentRequiredTaskRefresh();
            public function reservisionNotBlockTaskRefresh();
            public function reservisionNotConfermTaskRefresh();
             public function OOandOSReturnRefresh();
             public function cleanRoomsRefresh();
                 public function publicRefreshTask($functionName,$isNeedHotelDat = null,$taskName);

             
   // public function destroy($id);
}
