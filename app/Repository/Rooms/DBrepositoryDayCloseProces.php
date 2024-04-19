<?php
namespace App\Repository\Rooms;

use App\helpers;
use Carbon\Carbon;
use App\Models\Task;
use App\Models\Guests;
use App\Models\Setting;
use App\Models\preCharge;
use App\Models\OordService;
use App\Models\ChargeRouted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Models\Tenant;
use Laravel\Sanctum\PersonalAccessToken;
use App\Http\Controllers\CloseSalesController;
use App\Http\Controllers\ExtendStayController;
use App\Http\Controllers\TransactionController;
use Spatie\Multitenancy\Models\Concerns\UsesTenantModel;
use App\Repositoryinterface\RepositoryTaskDetailsinterface;
use App\Repositoryinterface\Rooms\RepositoryDayCloseProcesInterface;
use App\Repositoryinterface\Profiles\Calculate\RepositoryDayCloseRecordInterface;
use Illuminate\Support\Facades\Auth;
class DBrepositoryDayCloseProces  implements RepositoryDayCloseProcesInterface 
{
    private $guestModel;
    private $settingModel;
    private $tenantModel;
    private $oordServiceModel;
    private $preChargeModel;
    private $chargeRoutedModel;
    private $taskModel;
    
    private $extendStaycontroller;
    private $transactionController;
    private $CloseSalesController;
    
    private $dayCloseRecordInterface;
    private $taskInterface;
    
    private $hotelDate;
    private $tenant;
    
    use UsesTenantModel,helpers;
    
    public function __construct(Guests $guestModel, Setting $settingModel,Tenant $tenantModel,Task $taskModel,ExtendStayController $extendStaycontroller,
    TransactionController $transactionController,OordService $oordServiceModel,
    preCharge $preChargeModel, CloseSalesController $CloseSalesController,ChargeRouted $chargeRoutedModel,
    RepositoryDayCloseRecordInterface $dayCloseRecordInterface,RepositoryTaskDetailsinterface $taskInterface)
    {
        $this->extendStaycontroller = $extendStaycontroller;
        $this->guestModel = $guestModel;
        $this->oordServiceModel = $oordServiceModel;
        $this->oordServiceModel = $oordServiceModel;
        $this->preChargeModel = $preChargeModel;
        $this->settingModel = $settingModel;
        $this->chargeRoutedModel = $chargeRoutedModel;
        $this->taskModel = $taskModel;
        
        $this->extendStaycontroller = $extendStaycontroller;
        $this->transactionController = $transactionController;
        $this->CloseSalesController = $CloseSalesController;
        
        $this->dayCloseRecordInterface = $dayCloseRecordInterface;
        $this->taskInterface = $taskInterface;
    //    $tenant = Tenant::current();
    //    $this->hotelDate = cache()->get('settings_'. $tenant->id)->first()->hotel_date;
      //  $this->hotelDate = Setting::select('hotel_date')->first();
    }
    
    
    public function getExpectedCheckOut()
    {

        // $hotelDate =   $this->hotelDate;
        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;
        // dd($hotelDate);

        $expectedCeckoutGuest = $this->guestModel->whereDate('out_date', $hotelDate)
            ->where('is_reservation' , 0)->where('checked_out',0)->get();
        if($expectedCeckoutGuest->first())
        {
            return  $expectedCeckoutGuest;

        }else{
            return  null;
        }
    }
    public function extendStay($requests)
    {
       // dd($requests);
        $expectedCeckoutGuest = $this->guestModel->where('id',$requests['guest_id'][0])->first();
        $outDateFrom =  $expectedCeckoutGuest->out_date;
        $outDateTo = Carbon::parse($expectedCeckoutGuest->out_date)->addDay();
        if($expectedCeckoutGuest)
        {
            foreach($requests['guest_id'] as $guest)
            {
                $array = [
                    'guest_id' => $guest,
                    'out_date_from' => $outDateFrom,
                    'out_date_to' => $outDateTo,
                ];
                $request = new Request;
                $request->merge($array);
                $guestAfterExtendStay = $this->extendStaycontroller->store($request);
            }
            return $guestAfterExtendStay;
        }else{
            return false;
        }
        
    }

    
   
public function getExpectedCheckIn()
{
    $hotelDate = Setting::select('hotel_date')->first()->hotel_date;

    $expectedCheckInGuest = $this->guestModel->where('in_date', $hotelDate)->
    where('is_reservation',true)->where('is_checked_in',false)
    ->where('is_cancel',false)->get();
  //  dd($expectedCheckInGuest->first()->id);
    if($expectedCheckInGuest->first())
    {
        return $expectedCheckInGuest;
    }else{
        return null;
    }
}

public function noShow($request)
{
    $expectedCheckInGuest = $this->guestModel->where('id',$request['guest_id'][0])->first();
    if($expectedCheckInGuest) {
       // foreach($request['guest_id'] as $guest) {
            $updateStatus = $this->guestModel->whereIn('id',$request['guest_id'])->update([
                'res_status' => 'NSH',
                'is_cancel' => true
            ]);
     //   }

        return true;
    } else {
        return false;
    }

}

public function OrderAndService()
{ 
    $hotelDate = Setting::select('hotel_date')->first()->hotel_date;

    $roomInOrderAndServicesToExtendstay =  $this->oordServiceModel->where('end_date',$hotelDate)->where('is_return',0)->with('room')->get();
   // dd( $roomInOrderAndServicesToExtendstay);
    if( $roomInOrderAndServicesToExtendstay->first())
    {
        return  $roomInOrderAndServicesToExtendstay;
    }else{
        return null;
    }
}

public function toExtendOOrdServicesOneDay($request)
    {
        //dd($request);
        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;

        $extendOOrderOneDayStatus = $this->oordServiceModel->whereIn('id',$request->oordServicesID)->update([
            'end_date' => Carbon::parse($hotelDate)->addDay()
        ]);
        //dd($extendOOrderOneDayStatus);
        return $extendOOrderOneDayStatus;
    }
    public function getPrechargeDataTransfearToTransaction()
    {
        //dd($this->settings()->first()->hotel_date);
        $hotelDate = $this->settings()->first()->hotel_date;

        $PrechargeDataTransfearToTransaction = $this->preChargeModel->where('hotel_date',$hotelDate)
        ->whereHas('guest',function($q){
            $q->where('is_checked_in',1);
            $q->where('is_reservation',0);
        })
        ->with(['ledger','user'])
        ->with(['guest'=>function($q){$q->with('window');}])
        ->get();
        if( $PrechargeDataTransfearToTransaction->first())
        {
            return $PrechargeDataTransfearToTransaction;
        }else{
            return null;
        }
    }
/**
*$request come from function storeFromPreChargeToTransactionPage($ids)
*$ids is request accept "pre_charge_id":[1,2] 
*/
    public function storeFromPreChargeToTransaction($requests)
    {
  //dd('storeFromPreChargeToTransaction');
  $transCollection = 0;
  $count = 99999999;
  $transactionParentID = 0;
        foreach($requests->preCharge as $request)
        {
            if($transCollection != $request['transaction_collection'])
            {
                $transCollection = $request['transaction_collection'];
                $request['transaction_collection'] = null;
                $count =0;
            }
                if($count != 0)
                {
                $request['transaction_collection'] = $transactionParentID;
                //$count++;
                }

            $data = new Request;
            $data->merge($request);
           $dataStoredInTransaction = $this->transactionController->store($data);
           $transactionParentID = $dataStoredInTransaction->original['transaction']->id;
           $count++;
           if(empty($dataStoredInTransaction->original['errors']) && !empty($dataStoredInTransaction->original['transaction']->guest_id))
           {
            $deleteFromPreCharge = $this->preChargeModel->where('id',$request['pre_charge_id'])->delete();
           }
           
        }

        return true;
    }
    
    public function storeFromPreChargeToTransactionPage($ids)
    {
        $prechargeNotTrnsfer =[];
        $allprecharge=collect(["preCharge"=>collect([])]);
        $getPrechargeDataTransfearToTransaction = $this->preChargeModel->whereIn('id',$ids->pre_charge_id)
        ->with(['ledger','user'])
        ->with(['guest'=>function($q){$q->with('window');}])
        ->get();
        if($getPrechargeDataTransfearToTransaction)
        {
           foreach($getPrechargeDataTransfearToTransaction as $precharge)
           {
           //
             $getChargeRouteds = $this->chargeRoutedModel::where('guestID_from', $precharge->guest_id)
             ->where('window_id_from', $precharge->guest->window->first()->id)->where('ledgerID',$precharge->ledger_id)
                ->first();
           //
          // dump($precharge->guest->id);
            if($precharge->guest->is_checked_in == 0)
            {
               // array_push($prechargeNotTrnsfer,$precharge->id);
                continue;
                //break;
            }
            //dd($precharge->ledger->name);
               $prechargecollection['pre_charge_id'] = $precharge->id;
               $prechargecollection['transaction_collection'] = $precharge->transaction_collection;
               $prechargecollection['guest_id'] =($getChargeRouteds) ? $getChargeRouteds->guestID_to : $precharge->guest_id;
               $prechargecollection['tr_guest_id_from'] =($getChargeRouteds) ? $getChargeRouteds->guestID_from : null;
               $prechargecollection['res_id']=null;
               $prechargecollection['room_id'] = $precharge->guest->room_id;
               $prechargecollection['hotel_date'] = $precharge->hotel_date;
               $prechargecollection['window_id'] =($getChargeRouteds) ? $getChargeRouteds->window_id_to : $precharge->guest->window->first()->id;
               $prechargecollection['tr_window_id_from'] =($getChargeRouteds) ? $getChargeRouteds->window_id_from :null;
               $prechargecollection['ledger_id'] = ($getChargeRouteds) ? $getChargeRouteds->ledgerID : $precharge->ledger_id;
               $prechargecollection['payment_type_id'] = null;
               $prechargecollection['charge_amount'] = $precharge->amount;
               $prechargecollection['payment_amount'] = null;
               $prechargecollection['trans_type'] = "Example Transaction";/**                   */
               $prechargecollection['recd_type'] = "Example Transaction";/**                   */
               $prechargecollection['ref_id'] = null;
               $prechargecollection['description'] ="(Auto Post) ".$precharge->ledger->name ;/**                   */
               $prechargecollection['is_reservation'] = $precharge->guest->is_reservation;
               $prechargecollection['invoice_id'] = null;
               $prechargecollection['inc'] = 1;/**                   */
               $prechargecollection['voided_at'] = null;
               //$prechargecollection['created_by'] = 1;
               $allprecharge['preCharge']->push($prechargecollection);
               $array = $allprecharge->all();
           
              $request = new Request;
              $request->merge($array);

           }
           if(empty($request))
           {
            return 'notFound'; 
           }
          $getPrechargeDataTransfearToTransaction = $this->storeFromPreChargeToTransaction($request);
return true;
        }else{
            return 'notFound';
        }
    }


public function testSchedual()
{
    $this->guestModel->where('id',1)->update([
        'folio_no' => '9999'
    ]);
}

public function storeDayCloseSalse()
{
    $this->CloseSalesController->StoreData_Of_Charge();
    $this->CloseSalesController->StoreData_Of_Payment();
}

public function finalDayClose()
{

    if(auth()->user())
    {
        $authToken =  auth()->user()->tokens()->latest()->first()->id;
        //  $authToken =  request()->user()->currentAccessToken()->id;
          $tokens =PersonalAccessToken::where('id', '!=', $authToken)->get();
          foreach ($tokens as $token) {
              $token->delete();
          }

    }
  
        $hotelDate = $hotelDate = $this->settingModel::select('hotel_date')->first();
    $this->dayCloseRecordInterface->store($hotelDate);

    $this->storeDayCloseSalse();

    $hotelDataAfterAddDay = Carbon::parse( $hotelDate->hotel_date)->addDay();
    $updateStatus = $this->settingModel::whereDate('hotel_date',$hotelDate->hotel_date)->update([
        'hotel_date' => $hotelDataAfterAddDay
    ]);

    $tasks = $this->taskModel->get();
    foreach($tasks as $task)
    {
        $this->taskInterface->store($task);
    }
   
    return $hotelDataAfterAddDay;
}

}
