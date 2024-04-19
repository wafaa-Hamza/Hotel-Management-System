<?php
namespace App\Repositoryinterface\Profiles\GuestProfile;

interface RepositoryChargeRoutedInterface{
   // public function index();
   // public function inhousPagination();
     public function store($request);
     public function storeDefualtChargeRouted($request);
       public function show($id);
       public function update($request, $id);
       public function destroy($id);
}
