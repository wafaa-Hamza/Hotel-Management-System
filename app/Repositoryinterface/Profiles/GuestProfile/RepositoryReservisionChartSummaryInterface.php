<?php

namespace App\Repositoryinterface\Profiles\GuestProfile;

interface RepositoryReservisionChartSummaryInterface
{
  public function allRoom();
  public function expectedArrivalInDate($date);
  public function availableRoomsInDate($date);
  public function avrageRoomRate($date);
  public function expectedOut($date);
  public function totalNotCon($date);
  public function totalGrt($date);
  public function OCRooms($date);
  public function OORomms($date);
  public function OSRooms($date);
  public function netRooms($date);
  public function reservedRooms($date);
  public function roomType();
  public function getCountOfRoomType($date);
  public function reservisionChartSummary($request);
  
}
