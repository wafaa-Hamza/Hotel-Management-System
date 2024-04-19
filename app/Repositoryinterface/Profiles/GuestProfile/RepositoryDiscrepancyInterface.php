<?php
namespace App\Repositoryinterface\Profiles\GuestProfile;

interface RepositoryDiscrepancyInterface{
    public function index();
     public function store($request);
       public function show($id);
       public function showByID($id);
       public function update($request, $id);
       public function destroy($id);
}
