<?php
namespace App\Repositoryinterface\Profiles\GuestProfile;

interface RepositoryPreChargeInterface{
  public function getPreChargeDAtaForGest($guestID);
   
      public function preChargeStore($request);
      public function addTransactionCollection($data);
      public function preChargeCalckAmount($resRateDaysRmRate,$mealOrMealPackagePrice);
      public function destroy($id);

}