<?php
namespace App\Repository\profiles\guestProfile;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Guests;
use App\Models\RoomType;
use App\Models\preCharge;
use App\Models\ResRateDay;
use App\Models\OordService;
use App\Models\guest_profile;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\GuestProfileRequest;
use App\Models\Meal;
use App\Models\Setting;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryPreChargeInterface;
use Spatie\Multitenancy\Models\Tenant;

class DBrepositoryPreCharge implements RepositoryPreChargeInterface
{
    private $resRateDays;
    private $preChargeModel;
    private $settingModel;
    private $mealModel;
    private $tenant;
    public function __construct(ResRateDay $resRateDays, preCharge $preChargeModel,Tenant $tenant,Setting $settingModel,Meal $mealModel)
    {
        $this->resRateDays = $resRateDays;
        $this->preChargeModel = $preChargeModel;
        $this->settingModel = $settingModel;
        $this->mealModel = $mealModel;
        $this->tenant = $tenant;
    }

    public function getPreChargeDAtaForGest($guestID)
    {
        $resRateDays = $this->resRateDays::where('guest_id', $guestID)
        ->with('mealPackage.meal', 'rateCode.ledger','meal')
        ->get();
        return  $resRateDays;
    }

    public function preChargeCalckAmount($resRateDaysRmRate, $mealOrMealPackagePrice)
    {
        $amount = $resRateDaysRmRate - $mealOrMealPackagePrice;
        return $amount;
    }

    public function addTransactionCollection($data)
    {
        $saveData = $this->preChargeStore($data);
        $transaction_collection=$this->preChargeModel::where('guest_id', $saveData['data']->guest_id)
        ->where('transaction_collection',null)
        ->get();
       
                foreach($transaction_collection as $collect) 
                {

                    $collect->update([
                        'transaction_collection' => $saveData['firstID']
                    ]);
                }
           
    }
    // public function addTransactionCollection($data)
    // {
    //    // dd($data->first()->['data']);
    //     $now = Carbon::now()->format('Y-m-d');
    //     $exploadNow = explode('-',$now);
    //     $collectDate = $exploadNow[0].$exploadNow[1].$exploadNow[2];
    //    // dd($collectDate);
    //     $saveData = $this->preChargeStore($data);
    //     $transaction_collection=$this->preChargeModel::
    //     where('transaction_collection',null)
    //     ->get();
    //     foreach($transaction_collection as $collect)
    //     {
    //         $collectionValue = $data->first()->guest_id."_".$collectDate;
    //        // dd($collectionValue);
    //         $collect->update([
    //              'transaction_collection' => $collectionValue
    //                ]);
    //     }
    // }

public function preChargeStore($request)
{

  // dd($request);

    $dataOfPreCharge=collect(['a']);
    $now = Carbon::now()->format('Y-m-d');
    $lastID=$this->preChargeModel::orderBy('id', 'desc')->latest()->first();
    $maxTransactionCollection=$this->preChargeModel::max('transaction_collection');
   
    if(! $maxTransactionCollection)
    {
        $maxTransactionCollection =0;
    }
    $newTransCollection =  $maxTransactionCollection; 
    $count = 0;
    $preCharge=false;
    $firstInsertPreChargeID=false;
 // dd($request->first()->rateCode);
foreach($request as $resRateDays) {
    $newTransCollection ++; 
    $preCharge = new $this->preChargeModel();
    $mealPreCharge = new $this->preChargeModel();
    $preCharge->hotel_date = $resRateDays->day_date;
    $preCharge->sys_data = $now;
    $preCharge->guest_id = $resRateDays->guest_id;
    $preCharge->transaction_collection =  $newTransCollection;
    
    if($resRateDays->rateCode == null) {
        // $tenantID = $this->tenant->current()->id;
        $tenantID = null;
        if($tenantID) {
            $cacheData = cache()->get('settings_'.$tenantID);
            $preCharge->ledger_id = $cacheData->first()->def_ledger_id;
        } else {
            $ledgerID = $this->settingModel::select('def_ledger_id')->first()->def_ledger_id;
            $preCharge->ledger_id = $ledgerID;
        }

    } else {
        $preCharge->ledger_id = $resRateDays->rateCode->ledger->id;
    }

    $preCharge->user_id = auth()->user()->id;
        if($resRateDays->meal_id == null && $resRateDays->meal_package_id != null)
        {
           // dd($resRateDays);
            $preCharge->amount = $this->preChargeCalckAmount($resRateDays->rm_rate, $resRateDays->mealPackage->meal->sum('price'));
       $preCharge->save();
foreach ($resRateDays->mealPackage->meal as $meal ) {
    $mealPreCharge = new $this->preChargeModel();
    $mealPreCharge->hotel_date = $resRateDays->day_date;
    $mealPreCharge->sys_data = $now;
    $mealPreCharge->guest_id = $resRateDays->guest_id;
    $mealPreCharge->ledger_id =  $meal->ledger_id;
    $mealPreCharge->amount =  $meal->price;
    $mealPreCharge->user_id = auth()->user()->id;
    $mealPreCharge->transaction_collection =  $newTransCollection;

    $mealPreCharge->save();
}


            
           }elseif($resRateDays->meal_id == null && $resRateDays->meal_package_id == null)
           {
            $preCharge->amount = $this->preChargeCalckAmount($resRateDays->rm_rate, 0);
                   $preCharge->save();

           }  
           
           else{

            $preCharge->amount = $this->preChargeCalckAmount($resRateDays->rm_rate, $resRateDays->meal->price);
                   $preCharge->save();

            //to save another precharge for mail
            $mealPreCharge->hotel_date = $resRateDays->day_date;
            $mealPreCharge->sys_data = $now;
            $mealPreCharge->guest_id = $resRateDays->guest_id;
            $mealPreCharge->ledger_id =  $resRateDays->meal->ledger_id;
            $mealPreCharge->amount =  $resRateDays->meal->price;
            $mealPreCharge->user_id = auth()->user()->id;
            $mealPreCharge->transaction_collection =  $newTransCollection;

            $mealPreCharge->save();

           }
         
      //  $preCharge->created_at = '2023-04-07 12:35:57';
        //$preCharge->transaction_collection = 1;
       //dd(  $preCharge);
       
       if($count == 0)
        $firstInsertPreChargeID = $preCharge->id;
        $count++;
    }
    return ['data' => $preCharge, 'firstID' => $firstInsertPreChargeID];


}

public function destroy($id)
{
    $preChargData = $this->preChargeModel->where('guest_id',$id)->get();
    if($preChargData->first())
    {
        foreach($preChargData as $preCharge)
        {
            $preCharge->delete();
        }
        return true;
    }else{
        return null;
    }
 
}






}
