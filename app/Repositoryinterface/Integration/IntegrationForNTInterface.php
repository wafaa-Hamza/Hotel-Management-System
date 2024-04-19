<?php
namespace App\Repositoryinterface\Integration;

interface IntegrationForNTInterface{
    public function NTMPCreateOrUpdate($guest_id, $cuFlag, $transactionTypeId);
    public function NTMPhandelDateForNTMP($request);
    public function NTMPhandelTimeForNTMP($time);
    public function NTMPGender($roomType);
    public function NTMPWayOfPayment($wayOfPayment);

    public function NTMPCancelBooking($request,$cancelReason);
    public function NTMPcancelReason($request);
    public function NTMPExpensesDetails($request);
    public function NTMPOccupancyUpdate($request);


}
