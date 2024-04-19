<?php
namespace App\Repositoryinterface\Profiles\GuestProfile;

interface RepositoryWindowInterface{
  //  public function index();
    public function store($request,$guest_id,$windowID);
      public function show($id);
      //public function update($request, $id);
  
}
