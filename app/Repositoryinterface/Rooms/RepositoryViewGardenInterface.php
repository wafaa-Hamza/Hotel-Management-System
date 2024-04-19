<?php
namespace App\Repositoryinterface\Rooms;

interface RepositoryViewGardenInterface{
    public function index();
     public function store($request);
     public function show($id);
     public function update($request, $id);
     public function destroy($id);
     
}
