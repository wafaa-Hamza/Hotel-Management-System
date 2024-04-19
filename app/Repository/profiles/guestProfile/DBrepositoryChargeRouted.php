<?php
namespace App\Repository\profiles\guestProfile;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Guests;
use App\Models\RoomType;
use App\Models\OordService;
use App\Models\ChargeRouted;
use App\Models\guest_profile;
use App\Models\Ledger;
use Illuminate\Support\Facades\DB;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryChargeRoutedInterface;

class DBrepositoryChargeRouted implements RepositoryChargeRoutedInterface {
    private $guestModel; 
    private $chargeRoutedModel; 
    private $ledgerModel; 

    public function __construct(Guests $guestModel,ChargeRouted $chargeRoutedModel,Ledger $ledgerModel)
    {
        $this->guestModel = $guestModel;
        $this->chargeRoutedModel = $chargeRoutedModel;
        $this->ledgerModel = $ledgerModel;
     
    }

public function store($request)
{
    if($request->has('status'))
    {
    foreach($request->ledgerID as $ledger)
    {
        $storeChargeRouted = $this->chargeRoutedModel::create(
            [
                'guestID_from' =>$request->guestID_from,
                'ledgerID' =>$ledger,
                'guestID_to' =>$request->guestID_to,
                'window_id_from' =>$request->window_id_from,
                'window_id_to' =>$request->window_id_to,
            ]
        );
    }

    $storeChargeRouted = $this->guestModel::where(function($q) use($request){
        if($request->status=='join'){
            $q->where('id',$request->guestID_from)->orWhere('id',$request->guestID_to)->update([
                'is_join' => 1,
            ]);
        }
        if($request->status=='connect'){
            $q->where('id',$request->guestID_from)->orWhere('id',$request->guestID_to)->update([
                'is_connected' => 1,
            ]);
        }
    })->get();
}else{

    
    foreach($request->routingTo as $oneRoutingTo)
    {
        $storeChargeRouted = $this->chargeRoutedModel::create(
            [
                'guestID_from' =>$request->guestID_from,
                'ledgerID' =>$oneRoutingTo['ledgerID'],
                'guestID_to' =>$oneRoutingTo['guestID_to'],
                'window_id_from' =>$request['window_id_from'],
                'window_id_to' =>$oneRoutingTo['window_id_to'],
            ]
        );
    }
}
  
  
    $ChargeRouted =  $this->chargeRoutedModel::where('guestID_from',$request->guestID_from)->with('guestTo',
       'ledger',
       'guestFrom',
       'windowFrom',
       'windowTo',
       )->get();

    return  $ChargeRouted;
}

public function storeDefualtChargeRouted($request)
{
    $ledgerDefualt = $this->ledgerModel::where('defult_routing',1)->get();
    foreach($ledgerDefualt as $ledger)
    {
        $storeChargeRouted = $this->chargeRoutedModel::create(
            [
                'guestID_from' =>$request['guest_id_from'],
                'ledgerID' =>$ledger->id,
                'guestID_to' =>$request['guest_id_to'],
                'window_id_from' =>$request['window_id_from'],
                'window_id_to' =>$request['window_id_to'],
                'is_join' => 1,
            ]
        );
    }
}
public function show($id)
{
   // $getChargeRouteds = $this->chargeRoutedModel::where('guestID_from', $id)->get();
    $getChargeRouteds = $this->chargeRoutedModel::with(['guestTo.room','guestTo.profile','windowTo'])->where('guestID_from', $id)->get();

    return $getChargeRouteds;
  
}
public function update($request, $id)
{
    $getChargeRouteds = $this->chargeRoutedModel::where('guestID_from', $id)->get();
    if($getChargeRouteds->first()){
        foreach($getChargeRouteds as $getChargeRouted){
            $getChargeRouted->delete();
        }
        foreach($request->ledgerID as $ledger)
        {
        $updateChargeRouted = $this->chargeRoutedModel::where('id',$id)->create(
            [
                'guestID_from' =>$request->guestID_from,
                'ledgerID' =>$ledger,
                'guestID_to' =>$request->guestID_to,
                'window_id_from' =>$request->window_id_from,
                'window_id_to' =>$request->window_id_to,
            ]
        );
    }
    $storeChargeRouted = $this->guestModel::where(function($q) use($request){
        if($request->status=='join'){
            $q->where('id',$request->guestID_from)->orWhere('id',$request->guestID_to)->update([
                'is_join' => 1,
                'is_connected' => 0,
            ]);
        }
        if($request->status=='connect'){
            $q->where('id',$request->guestID_from)->orWhere('id',$request->guestID_to)->update([
                'is_connected' => 1,
                'is_join' => 0,
            ]);
        }
    })->get();
    $ChargeRouted =  $this->chargeRoutedModel::where('guestID_from',$request->guestID_from)->with('guestTo',
       'ledger',
       'guestFrom',
       'windowFrom',
       'windowTo',
       )->get();

        return  $ChargeRouted;
    }else{
        return null;
    }       
}

public function destroy($id)
{
    $getChargeRouteds = $this->chargeRoutedModel::where('guestID_from', $id)->get();
    if($getChargeRouteds->first()){
        foreach($getChargeRouteds as $getChargeRouted){
            $getChargeRouted->delete();
        }
        $storeChargeRouted = $this->guestModel::where('id',$id)->orWhere('id',$getChargeRouted->guestID_from)
        ->update([
            'is_connected' => 0,
            'is_join' => 0,
        ]);


        return $getChargeRouteds;
}else{
    return null;
}

}    
 
}      
