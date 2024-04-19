<?php

namespace App\Repositoryinterface\Profiles\GuestProfile;

interface RepositoryChartOfAccountsInterface
{
      public function index();
      public function getAllLevel();
      // public function inhousPagination();
      public function store($request);
      public function chartOfAccountStoreHelper($parentID);
      public function accountCode($accountLevel, $parentID);
      public function update($request, $id);
}
