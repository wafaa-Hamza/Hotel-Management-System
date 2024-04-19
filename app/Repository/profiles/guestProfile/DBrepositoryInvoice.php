<?php
namespace App\Repository\profiles\guestProfile;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Guests;
use App\Models\Ledger;
use App\Models\Invoice;
use App\Models\RoomType;
use App\Models\OordService;
use Illuminate\Support\Str;
use App\Models\ChargeRouted;
use App\Models\guest_profile;
use Illuminate\Support\Facades\DB;
use App\Repositoryinterface\Generalinterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryInvoiceInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryChargeRoutedInterface;

class DBrepositoryInvoice implements RepositoryInvoiceInterface{
    private $guestModel; 
    private $chargeRoutedModel; 
    private $ledgerModel; 
    private $invoiceModel; 
    private $generalInterface; 

    public function __construct(Generalinterface $generalInterface,Invoice $invoiceModel,Guests $guestModel,ChargeRouted $chargeRoutedModel,Ledger $ledgerModel)
    {
        $this->guestModel = $guestModel;
        $this->chargeRoutedModel = $chargeRoutedModel;
        $this->ledgerModel = $ledgerModel;
        $this->invoiceModel = $invoiceModel;
        $this->generalInterface = $generalInterface;
     
    }

    public function index()
    {
        $invoice =  $this->invoiceModel::with('window',
        'user',
        )->get();
        return $invoice;
    }
    public function invoicePagination()
    {
        $invoice =  $this->invoiceModel::with('window',
        'user',
        )->paginate(request()->segment(count(request()->segments())));
        return $invoice;
    }

    public function generateInvoiceNumber()
    {
        $invoiceNumber = $this->invoiceModel::max('invoice_number');
        if($invoiceNumber)
        {
            $invoiceNumber = $invoiceNumber+1;

        }else{
            $invoiceNumber = 1;
        }
        return $invoiceNumber;

    }

public function store($request)
{
    $this->generateInvoiceNumber();
  $invoiceOpject = new $this->invoiceModel;
  $uniqueCheck = $invoiceOpject->where('guest_id',$request['guest_id'])->where('window_id',$request['window_id'])->first();

//dd($uniqueCheck);
  if($uniqueCheck)
{
    return null;
}
$invoiceOpject->uuid = Str::uuid();
$invoiceOpject->guest_id = $request['guest_id'];
$invoiceOpject->window_id = $request['window_id'];
$invoiceOpject->invoice_number = $this->generateInvoiceNumber();
$invoiceOpject->total_room_charge = $this->generalInterface->total_roomCharge($request);
$invoiceOpject->total_fb_charge = $this->generalInterface->total_fb_charge($request);
$invoiceOpject->total_other_charge = $this->generalInterface->total_other_charge($request);
$invoiceOpject->total_payment = $this->generalInterface->total_payment($request);
$invoiceOpject->total_taxes = 0;
$invoiceOpject->user_id = auth()->id();
$invoiceSave = $invoiceOpject->save();
if($invoiceSave == true)
{
    $invoice =  $this->invoiceModel::where('guest_id',$request['guest_id'])->with('window',
    'user',
    )->get(['invoice_number']);
    return   $invoice;
}else{
    return null;
}
    
}

public function update($request, $id)
{
    $this->generateInvoiceNumber();
    if($request->window_id)
    {
        $invoiceData = $this->invoiceModel::where('guest_id',$id)->where('window_id',$request['window_id'])->first();
        $updateInvoice = $this->invoiceModel::where('guest_id',$id)->where('window_id',$request['window_id'])->update([
            "window_id" =>(!empty($request['window_id']))? $request['window_id'] : $invoiceData->window_id,
            "total_room_charge" =>(!empty($request['total_room_charge']))? $request['total_room_charge'] : $invoiceData->total_room_charge,
            "total_fb_charge" =>(!empty($request['total_fb_charge']))? $request['total_fb_charge'] : $invoiceData->total_fb_charge ,
            "total_other_charge" =>(!empty($request['total_other_charge']))? $request['total_other_charge'] : $invoiceData->total_other_charge,
            "total_payment" =>(!empty($request['total_payment']))? $request['total_payment'] : $invoiceData->total_payment
        ]);
    }else{
$invoiceData = $this->invoiceModel::where('guest_id',$id)->where('window_id',$request['window_id'])->first();
        $updateInvoice = $this->invoiceModel::where('guest_id',$id)->update([
            "window_id" =>$request['window_id'],
            "total_room_charge" =>$request['total_room_charge'],
            "total_fb_charge" =>$request['total_fb_charge'],
            "total_other_charge" =>$request['total_other_charge'],
            "total_payment" =>$request['total_payment']
        ]);
    }


  if($updateInvoice)
  {
      $invoice =  $this->invoiceModel::where('guest_id',$id)->with('window',
      'user',
      )->get();
  
      return  $invoice;
  }else{
      return null;
  }
}

public function show($id)
{
    $getChargeRouteds = $this->chargeRoutedModel::where('guestID_from', $id)->get();
    return $getChargeRouteds;
  
}


// public function destroy($id)
// {
//     $getChargeRouteds = $this->chargeRoutedModel::where('guestID_from', $id)->get();
//     if($getChargeRouteds->first()){
//         foreach($getChargeRouteds as $getChargeRouted){
//             $getChargeRouted->delete();
//         }
//         $storeChargeRouted = $this->guestModel::where('id',$id)->orWhere('id',$getChargeRouted->guestID_from)
//         ->update([
//             'is_connected' => 0,
//             'is_join' => 0,
//         ]);


//         return $getChargeRouteds;
// }else{
//     return null;
// }

// }    
 
}      