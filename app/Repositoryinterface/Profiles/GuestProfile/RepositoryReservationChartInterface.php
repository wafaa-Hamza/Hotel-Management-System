<?php

namespace App\Repositoryinterface\Profiles\GuestProfile;

interface RepositoryReservationChartInterface
{
  public function getGuestIndividaul($date);
  public function getPaymaster($date);
  public function getGroupData($date, $dateOnly);
  public function reservationChart($request);
  
}
