<?php
namespace App\Repository\profiles\guestProfile;

use App\helpers;
use App\Http\Controllers\TransactionController;
use App\Models\ExtraBedAndMealPivotGuest;
use App\Models\ExtraBedOrMeal;
use App\Models\Guests;
use App\Models\Setting;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryExtraBedAndMealInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\Multitenancy\Models\Tenant;

class DBrepositoryExtraBedAndMeal implements RepositoryExtraBedAndMealInterface
{
    use helpers;
    private $extraBedAndMealPivotGuestModel;
    private $extraBedOrMealModel;
    private $guestModel;
    private $tenantModel;
    private $settingModel;
    private $transactionController;
    public function __construct(ExtraBedAndMealPivotGuest $extraBedAndMealPivotGuestModel, ExtraBedOrMeal $extraBedOrMealModel,
    Guests $guestModel,Tenant $tenantModel, TransactionController $transactionController, Setting $settingModel)
    {
        $this->extraBedAndMealPivotGuestModel = $extraBedAndMealPivotGuestModel;
        $this->extraBedOrMealModel = $extraBedOrMealModel;
        $this->guestModel = $guestModel;
        $this->tenantModel = $tenantModel;
        $this->settingModel = $settingModel;
        $this->transactionController = $transactionController;
    }

    public function index()
    {
        $extraBedOrMeal = $this->extraBedOrMealModel::with('ledger')->get();
        return $extraBedOrMeal;

    }
    public function show($id)
    {
        $extraBedOrMeal = $this->extraBedOrMealModel::where('id', $id)->first();
        return $extraBedOrMeal;

    }
    public function getExtraByGuestID($id)
    {
        $extraBedOrMeal = $this->guestModel::with('ExtraBedMeal.ExtraBedMeal')->where('id', $id)->first()->ExtraBedMeal;
        return $extraBedOrMeal;

    }

    public function store($request)
    {
        $extraBedOrMeal = $this->extraBedOrMealModel::create([
            'name' =>$request->name,
            'name_loc' =>$request->name_loc,
            'ledger_id' =>$request->ledger_id,
        ]);
        
        return $extraBedOrMeal;
    }
    public function storeExtraqForGuest($requests)
    {
        foreach($requests as $request)
        {
            $extraBedOrMeal = $this->extraBedAndMealPivotGuestModel::create([
                'guest_id' =>$request['guest_id'],
                'extra_id' =>$request['extra_id'],
                'amount' =>(Arr::has($request,'amount'))?$request['amount'] : null,
            ]);
        }
    
        
        return $extraBedOrMeal;
    }
    public function update($request, $id)
    {
        $extraBedOrMeal = $this->show($id);
        if ($extraBedOrMeal) {
            $this->extraBedOrMealModel::where('id', $id)->update([
                'name' => (!empty($request->name)) ? $request->name : $extraBedOrMeal->name,
                'name_loc' => (!empty($request->name_loc)) ? $request->name_loc : $extraBedOrMeal->name_loc,
                'ledger_id' => (!empty($request->ledger_id)) ? $request->ledger_id : $extraBedOrMeal->ledger_id,
            ]);

            $extraBedOrMeal = $this->show($id);

            return $extraBedOrMeal;
        } else {
            return null;
        }

    }

    public function destroy($id)
    {
        
        $extraBedOrMeal = $this->show($id);
        
        if ($extraBedOrMeal) {
            $dataDeleted = $extraBedOrMeal->delete();

            return $dataDeleted; 
        } else {
            return null;
        }

    }
    public function destroyExtraGuestPivot($id)
    {
        
        $pivote = $this->extraBedAndMealPivotGuestModel::where('id',$id)->first();
        
        if ($pivote) {
            $dataDeleted = $pivote->delete();

            return $dataDeleted; 
        } else {
            return null;
        }

    }

    public function getExteraOfCheckInGuest()
{
    $tenant = $this->tenantModel->current();
    if($tenant)
    {
        $setting = cache('settings_'.$tenant->id)->first();
    }else{
        $setting = $this->settingModel->first();
    }
     
    $checkInGuestsIDsData = $this->guestModel
    //::doesntHave('transactions')
    ::withCount(['transactions' => function($q)use($setting){
        $q->whereDate('hotel_date','=',$setting->hotel_date); 
        $q->where('description','Extra Posted');
    }])
    ->where('is_checked_in',1)->where('checked_out',0)->whereDate('in_date','<=',Carbon::parse($setting->hotel_date))
    ->whereDate('out_date','>=',Carbon::parse($setting->hotel_date))->get(['id','transactions_count']);
  //  dd($checkInGuestsIDsData->where('transactions_count','=',0)->pluck('id'));
 $checkInGuestsIDs = $checkInGuestsIDsData->where('transactions_count','=',0)->pluck('id');
//dd($checkInGuestsIDs);
//dd($checkInGuestsIDs->where('transactions_count','=',0));
        $extraBedOrMealForCheckedInGuest = $this->guestModel::
        select('id','profile_id','room_id')
        ->with(['room' => function($q){
            $q->select('id','rm_name_en','rm_name_loc');
        }])
        ->with(['ExtraBedMeal.ExtraBedMeal' => function($q){
            $q->select('id','name','name_loc','ledger_id');
            
            return $q;
        }])
        
        ->with(['profile' => function($q){
            $q->select('id','first_name','last_name');
            return $q;
        }])
        ->whereHas('ExtraBedMeal.ExtraBedMeal')
        ->whereIn('id', $checkInGuestsIDs->toArray())->get();
        return $extraBedOrMealForCheckedInGuest;


}

public function getextraDataForTransaction($extraIDs)
{
    $getDataForTransaction = $this->guestModel::
    whereHas('ExtraBedMeal',function($q)use($extraIDs){
        $q->whereIn('id',$extraIDs);

        return $q;
    })
   // ->select('id','profile_id','room_id')
    ->with(['room' => function($q){
        $q->select('id','rm_name_en','rm_name_loc');
    }])
     ->with(['ExtraBedMeal' => function($q)use($extraIDs){
        $q->whereIn('id',$extraIDs);
        $q->with(['ExtraBedMeal' => function($q1){
            return $q1->select('id','name','name_loc','ledger_id');    
        }]);
        
        
        return $q;
    }])
    ->with(['profile' => function($q){
        $q->select('id','first_name','last_name');
        return $q;
    }])
    ->with('window')
    ->get();
//dd( $getDataForTransaction);
    return $getDataForTransaction;
}

public function storeExtraDataToTransaction($request)
{
    $tenant = $this->tenantModel->current();
    if($tenant)
    {
        $setting = cache('settings_'.$tenant->id)->first();
    } else{
        $setting = $this->settingModel->first();
    }
    
    $extraGuestData = $this->getextraDataForTransaction($request->extraIDs);
   // dd($extraGuestData);
    $dataToTranaction =[];
    foreach( $extraGuestData as $oneExtraGuestData)
    {
        if($oneExtraGuestData->ExtraBedMeal)
        {
                 // $prechargecollection['pre_charge_id'] = $precharge->id;
                foreach($oneExtraGuestData->ExtraBedMeal as $oneExtra)
                {
                    $dataToTranaction['guest_id'] =$oneExtraGuestData->id;
                    $dataToTranaction['tr_guest_id_from'] =null;
                    $dataToTranaction['res_id']=null;
                    $dataToTranaction['room_id'] = $oneExtraGuestData->room_id;
                    $dataToTranaction['hotel_date'] = $setting->hotel_date;
                    $dataToTranaction['window_id'] =$oneExtraGuestData->window->first()->id;
                    $dataToTranaction['tr_window_id_from'] = null;
                    $dataToTranaction['ledger_id'] = $oneExtra->ExtraBedMeal->ledger_id;
                    $dataToTranaction['payment_type_id'] = null;
                    $dataToTranaction['charge_amount'] = $oneExtra->amount;
                    $dataToTranaction['payment_amount'] = null;
                    $dataToTranaction['trans_type'] = "C";/**                   */
                    $dataToTranaction['recd_type'] = "CHR";/**      charge             */
                    $dataToTranaction['ref_id'] = null;
                    $dataToTranaction['description'] ="Extra Posted";/**                   */
                    $dataToTranaction['is_reservation'] = $oneExtraGuestData->is_reservation;
                    $dataToTranaction['invoice_id'] = null;
                    $dataToTranaction['inc'] = 1;/**                   */
                    $dataToTranaction['voided_at'] = null;

                    $request = new Request;
                    $request->merge($dataToTranaction);
                    
                 $dataStoredInTransaction = $this->transactionController->store($request);
                }
                    

                

        
            
        }else{
            continue;
        }
        
    }

return true;

}





}