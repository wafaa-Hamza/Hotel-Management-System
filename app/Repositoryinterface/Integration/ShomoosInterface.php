<?php
namespace App\Repositoryinterface\Integration;

interface ShomoosInterface{
    public function index();
    public function store($request);
    public function show($id);
    public function getShomoosByGuest_id($id);
    public function update($request, $id);
    public function destroy($id);
   
   
   

}
