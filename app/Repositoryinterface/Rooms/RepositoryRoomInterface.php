<?php
namespace App\Repositoryinterface\Rooms;

interface RepositoryRoomInterface{
    public function index();
    public function roomPagination();
     public function store($request);
         public function storeMoreOneRoom($request);
     public function show($id);
     public function update($request, $id);
     public function destroy($id);
     public function geSoftDeletedData();
     public function restorDataTrashed($id);
     public function getAllInhousRooms();

     public function RoomStatus($request);
     public function getStatusByRoom($request);
     public function Max_sortOrder();
     public function copyRoomsFromFloorToAnother($request);
         public function getAvalDummyRoom();



}
