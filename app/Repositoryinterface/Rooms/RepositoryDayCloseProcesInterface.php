<?php
namespace App\Repositoryinterface\Rooms;

interface RepositoryDayCloseProcesInterface{
public function getExpectedCheckOut();
public function extendStay($requests);
public function getExpectedCheckIn();
public function noShow($requset);
public function OrderAndService();
public function toExtendOOrdServicesOneDay($request);
public function getPrechargeDataTransfearToTransaction();
    public function storeFromPreChargeToTransactionPage($ids);

public function storeFromPreChargeToTransaction($request);
public function testSchedual();
public function storeDayCloseSalse();
public function finalDayClose();






}
