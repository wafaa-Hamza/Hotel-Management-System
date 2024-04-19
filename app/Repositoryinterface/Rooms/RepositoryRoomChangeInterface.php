<?php
namespace App\Repositoryinterface\Rooms;

interface RepositoryRoomChangeInterface{
    public function index();
    public function roomChangPagination();
      public function store($request);
    //    public function show($id);
    //    public function update($request, $id);
    //    public function updateStatus($request, $id);
    //  public function destroy($id);
    //  public function geSoftDeletedData();
    //  public function restorDataTrashed($id);
}
