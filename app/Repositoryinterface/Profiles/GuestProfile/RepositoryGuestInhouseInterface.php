<?php

namespace App\Repositoryinterface\Profiles\GuestProfile;

interface RepositoryGuestInhouseInterface
{
  public function index();
  public function inhousPagination();
  public function getGuestInhous($request);
  public function getGuestInhouswithDates($request);
  public function changeRoomStatus($room_id, $status, $description, $hkStatus);
  public function store($request);
  public function storeMoreName($request, $guest_id);
  public function storeOnlyAndMoreName($request, $guest_id);
  public function deleteMoreName($guest_id);
  public function resRateDaysStore($request, $guestId);
  public function resRateDaysDelete($id);
  public function storeGroupProfile($request);
  public function isGroupProfileExist($request);

  public function storeGroupGuest($request);
  public function updateOneInGroupGuest($request, $guest_id);
  public function updateGroupGuest($request);
  public function updateAsPayMasterGuest($requests);
  public function addGuestInGroup($requests);
  public function updateGroupGuestByIds($request);
  public function getMaxOfGroupCodeFromGuest();
  public function deleteOnlyName($guest_id);

  public function showGroupRservision($id);
  // public function showGroupRservisioForSelected($id);

  public function destroy($id);

  public function show($id);
  public function update($request, $id);
  // public function updateReservissionOnly($request, $id);
  public function availability($reuest);
  public function AvailabilityScreenData($reuest);
  public function reservisionUpdate($request, $id);
  public function isGroupUpdate($request, $id);
  public function isDummy($request, $id);
  public function IsPM($request, $id);
  public function isCancel($id);
  public function groubeCancel($request);

  public function isCheckedIn($id);
  public function payMasterCheckIn($id);
  public function reInstate($id);
  public function checkOut($request);
  public function storeInTransactionController($request);
  public function storeInCompanyStatementController($request);

  public function cityLedgerCheckout($request);
  public function ReCheckin($request);
  public function guestAttachment($request);
  public function lockedReservision($id, $request);
  public function guestDeleteAttachment($id);
  public function updateuGestAttachment($request, $id);
  
  public function GetOcGuestByDate($request);
  public function storeGroupDetail($request,$guest_id);
  











  //  public function updateIsReservation($request, $id);
  //  public function destroy($id);
  //  public function geSoftDeletedData();
  //  public function restorDataTrashed($id);
}
