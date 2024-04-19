<?php

namespace App\Http\Controllers\Api\V1\Rooms;

use App\helpers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\PreChargeResource;
use App\Http\Resources\Rooms\RoomResource;
use App\Http\Requests\DayCloseProcesRequest;
use App\Http\Resources\GuestInhouseResource;
use App\Http\Resources\outOfServicesResource;
use App\Http\Requests\ToExtendOOrdServicesOneDayRequest;
use App\Repositoryinterface\Rooms\RepositoryDayCloseProcesInterface;

class DayCloseProcesController extends Controller
{
    use helpers;
    private $dayCloseProcesInterface;
    public function __construct(RepositoryDayCloseProcesInterface $dayCloseProcesInterface)
    {
        $this->dayCloseProcesInterface = $dayCloseProcesInterface;
    }
    
    public function getExpectedCheckOut()
    {
        $expectedCeckoutGuest =  $this->dayCloseProcesInterface->getExpectedCheckOut();
        if( $expectedCeckoutGuest)
        {
            return $this->apiResponse(new GeneralCollection($expectedCeckoutGuest,GuestInhouseResource::class),200);
        }else{

            return $this->apiResponse(['message' => 'not found']);
        }
    }

    public function extendStay(DayCloseProcesRequest $request)
    {
        $extendStayStatus = $this->dayCloseProcesInterface->extendStay($request);
        if($extendStayStatus)
        {
            return $this->apiResponse(['message' => 'updated']);
        }else{
            return $this->apiResponse(['message' => 'not found']);

        }
      
    }


    public function getExpectedCheckIn()
    {
        $expectedCheckIn = $this->dayCloseProcesInterface->getExpectedCheckIn();
        if($expectedCheckIn)
        {
            return $this->apiResponse(new GeneralCollection($expectedCheckIn,GuestInhouseResource::class),200);
        }else{
            return $this->apiResponse(['message' => 'not found']);

        }
        
        
    }

    public function noShow(DayCloseProcesRequest $request)
    {
        $noShow = $this->dayCloseProcesInterface->noShow($request);
        if($noShow)
        {
            return $this->apiResponse(['message' => 'updated']);
        }else{
            return $this->apiResponse(['message' => 'not found']);

        }
    }

    public function OrderAndService()
    {
        $roomInOrderAndServicesToExtendstay = $this->dayCloseProcesInterface->OrderAndService();
        if($roomInOrderAndServicesToExtendstay)
        {
            return $this->apiResponse(new GeneralCollection($roomInOrderAndServicesToExtendstay,outOfServicesResource::class),200);
        }else{
            return $this->apiResponse(['message' => 'not found']);

        }
        
    }

    public function ToExtendOOrdServicesOneDay(ToExtendOOrdServicesOneDayRequest $request)
    {
        $extendOOrderOneDayStatus = $this->dayCloseProcesInterface->toExtendOOrdServicesOneDay($request);
        if($extendOOrderOneDayStatus)
        {
            return $this->apiResponse(['message' => 'updated']);
        }else{
            return $this->apiResponse(['message' => 'not found']);

        }
    }

    public function getPrechargeDataTransfearToTransaction()
    {
        $PrechargeDataTransfearToTransaction = $this->dayCloseProcesInterface->getPrechargeDataTransfearToTransaction();
        if($PrechargeDataTransfearToTransaction)
        {
            return $this->apiResponse(new GeneralCollection($PrechargeDataTransfearToTransaction,PreChargeResource::class),200);
        }else{
            return $this->apiResponse(['message' => 'not found']);

        }
    }

    public function storeFromPreChargeToTransaction(ToExtendOOrdServicesOneDayRequest $request)
    {
       // $storeFromPreChargeToTransaction = $this->dayCloseProcesInterface->storeFromPreChargeToTransaction($request);
        $storeFromPreChargeToTransaction = $this->dayCloseProcesInterface->storeFromPreChargeToTransactionPage($request);
        if($storeFromPreChargeToTransaction)
        {
            return $this->apiResponse(['message' => 'true']);
        }else{
            return $this->apiResponse(['message' => 'not found']);

        }
    

    
    }

    public function finalDayClose()
    {
        $finalDayClose = $this->dayCloseProcesInterface->finalDayClose();
        if($finalDayClose)
        {
           return $this->apiResponse(['message' => 'Day Closed']);
        }else{
            return $this->apiResponse(['message' => 'Day Not Closed']);
        }
    }

    





}
