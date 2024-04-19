<?php
namespace App\Repositoryinterface\Profiles\GuestProfile;

interface RepositoryExtraBedAndMealInterface{
    public function index();
    public function store($request);
      public function show($id);
      public function update($request, $id);
      public function destroy($id);
      public function storeExtraqForGuest($request);
          public function getExtraByGuestID($id);
              public function destroyExtraGuestPivot($id);
              public function getExteraOfCheckInGuest();
              public function getextraDataForTransaction($extraIDs);
              public function storeExtraDataToTransaction($request);



  
}