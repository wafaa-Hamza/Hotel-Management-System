<?php
namespace App\Repositoryinterface\Profiles\GuestProfile;

interface RepositoryExpensesLedgerInterface{
    public function index();
    public function store($request);
      public function show($id);
      public function update($request, $id);
      public function destroy($id);
  
}
