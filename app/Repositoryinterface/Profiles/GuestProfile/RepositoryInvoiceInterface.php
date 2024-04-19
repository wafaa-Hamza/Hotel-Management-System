<?php
namespace App\Repositoryinterface\Profiles\GuestProfile;

interface RepositoryInvoiceInterface{
     public function index();
     public function invoicePagination();
     public function generateInvoiceNumber();
   // public function inhousPagination();
     public function store($request);
   //  public function storeDefualtChargeRouted($request);
    //   public function show($id);
       public function update($request, $id);
      // public function destroy($id);
}
