<?php

namespace App\Repositoryinterface\Profiles\GuestProfile;

interface RepositoryGuestProfileInterface
{
  public function index();
  public function seachProfile($request);
  public function profilePagination();
  public function store($request);
  public function show($id);
  public function update($request, $id);
  public function guest_profile_search($request);
  public function store_from_landlord($request);
  public function store_to_landlord($request);


  //  public function destroy($id);
  //  public function geSoftDeletedData();
  //  public function restorDataTrashed($id);
}
