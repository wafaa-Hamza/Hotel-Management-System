<?php

namespace App\Http\Controllers\Api\V1\History;

use App\Models\Guests;
use App\Models\Window;
use App\Models\GuestOut;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\WindowHistory;
use App\Models\transactionHistory;
use App\Http\Controllers\Controller;

class DayCloseController extends Controller
{
    private $transactionHistoryModel;
    private $transactionModel;
    private $guestModel;
    private $guestOutModel;
    private $windowModel;
    private $windowHistoryModel;

    public function __construct(transactionHistory $transactionHistoryModel, Transaction $transactionModel, Guests $guestModel, GuestOut $guestOutModel, Window $windowModel,WindowHistory $windowHistoryModel)
    {
        $this->transactionHistoryModel =$transactionHistoryModel;
        $this->transactionModel =$transactionModel;
        $this->guestModel =$guestModel;
        $this->guestOutModel =$guestOutModel;
        $this->windowModel =$windowModel;
        $this->windowHistoryModel =$windowHistoryModel;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       // $guest_ids=[];
       // $guests = $this->guestModel::where('checked_out',1)->get();

        // foreach($guests as $guestID)
        // {
        //     array_push($guest_ids,$guestID->id);
        // }
        $transactioClosedData = $this->transactionModel::with('taxes')
        ->whereHas('guest',function($q){$q->where('checked_out',1);})
        ->get();
//dd( $transactioClosedData);
        $windowClosedData = $this->windowModel::
        whereHas('guest',function($q){$q->where('checked_out',1);})
        ->get(['guest_id','window_number','window_name','invoice_number']);
//dd($windowClosedData);
        $taxesHistoryOpject = new $this->transactionHistoryModel();
        $taxesOpject = new $this->transactionModel();
        //dd($transactioClosedData->first());
        if($transactioClosedData->first())
        {
            foreach($transactioClosedData as $transaction)
            {
            $transactioHistoryStore = $this->transactionHistoryModel::create([
                'id' => $transaction->id,
                'transaction_collection' => $transaction->transaction_collection,
                'trans_no' => $transaction->trans_no,
                'guest_id' =>$transaction->guest_id ,
                'res_id' =>$transaction->res_id,
                'room_id' => $transaction->room_id,
                'hotel_date' => $transaction->hotel_date,
                'sys_date' => $transaction->sys_date,
                'ledger_id' => $transaction->ledger_id,
                'payment_type_id' => $transaction->payment_type_id,
                'charge_amount' => $transaction->charge_amount,
                'charge_without_taxes' => $transaction->charge_without_taxes,
                'payment_amount' => $transaction->payment_amount,
                'window_id' => $transaction->window_id,
                'trans_type' => $transaction->trans_type,
                'recd_type' => $transaction->recd_type,
                'ref_id' => $transaction->ref_id,
                'description' => $transaction->description,
                'is_reservation' => $transaction->is_reservation,
                'created_by' => $transaction->created_by,
                'is_void' => $transaction->is_void,
                'invoice_id' => $transaction->invoice_id,
                'updated_by' => $transaction->updated_by,
                'inc' => $transaction->inc,   
            ]);
       $transactionPivot = $transaction->taxes;
            foreach($transactionPivot as $pivot)
            { 
              $ID =  $taxesHistoryOpject->taxes()->id = $pivot->pivot->id;
             // dump( $ID);
              $tranactioID = $taxesHistoryOpject->taxes()->transaction_history_id = $pivot->pivot->transaction_id;
               $taxID = $taxesHistoryOpject->taxes()->tax_id  = $pivot->pivot->tax_id ;
               $amount = $taxesHistoryOpject->taxes()->amount	 = $pivot->pivot->amount	;
               $name = $taxesHistoryOpject->taxes()->name = $pivot->pivot->name;
               $nameLoc = $taxesHistoryOpject->taxes()->name_loc = $pivot->pivot->name_loc;
                $inc = $taxesHistoryOpject->taxes()->inc = $pivot->pivot->inc;
                //array_push($taxesData,['id' => $ID,'transaction_history_id' => $tranactioID, 'tax_id' => $taxID, 'amount' => $amount, 'name' =>  $name,'name_loc' =>  $nameLoc,'inc' => $inc]);
                $AddInHistoryTransactionPivote = $taxesHistoryOpject->taxes()->attach(['id' => $ID,'transaction_history_id' => $tranactioID, 'tax_id' => $taxID, 'amount' => $amount, 'name' =>  $name,'name_loc' =>  $nameLoc,'inc' => $inc]);
            }
            $transaction->forceDelete();
            }

        }
       
        if($windowClosedData->first())
        {
            foreach($windowClosedData as $data)
            {
                $windowHistoryOpject = new  $this->windowHistoryModel();
                $windowHistoryOpject->guest_id = $data->guest_id;
                $windowHistoryOpject->window_number = $data->window_number;

                $windowHistoryOpject->window_name = $data->window_name;

                $windowHistoryOpject->invoice_number = $data->invoice_number;

                $windowHistoryOpject->save();

                $data->where('guest_id', $data->guest_id)->where('window_number',$data->window_number)->delete();
            }

        }
               

    //   foreach($guests as $guest)
    //   {
    //       $Guests_History_Table=$this->guestOutModel::create([
    //        'id'                        =>$guest->id,
    //        'folio_no'                  =>$guest->folio_no,
    //        'in_date'                   =>$guest->in_date,
    //        'out_date'                  =>$guest->out_date,
    //        'original_out_date'         =>$guest->original_out_date,    
    //        'no_of_nights'              =>$guest->no_of_nights,
    //        'rm_rate'                   =>$guest->rm_rate,
    //        'taxes'                     =>$guest->taxes,
    //        'no_of_pax'                 =>$guest->no_of_pax,
    //        'hotel_note'                =>$guest->hotel_note,
    //        'client_note'               =>$guest->client_note,
    //        'way_of_payment'            =>$guest->way_of_payment,
    //        'profile_id'                =>$guest->profile_id,
    //        'company_id'                =>$guest->company_id,
    //        'room_id'                   =>$guest->room_id,
    //        'room_type_id'              =>$guest->room_type_id,
    //        'rate_code_id'              =>$guest->rate_code_id,
    //        'market_segment_id'         =>$guest->market_segment_id,
    //        'source_of_business_id'     =>$guest->source_of_business_id,
    //        'meal_id'                   =>$guest->meal_id,
    //        'created_by'                =>$guest->created_by,
    //        'created_inhousGuest_at'    =>$guest->created_inhousGuest_at,
    //        'checked_out'               =>$guest->checked_out,
    //        'checkout_by'               =>$guest->checkout_by,
    //        'checkout_at'               =>$guest->checkout_at,
    //        'is_reservation'            =>$guest->is_reservation,
    //        'res_status'                =>$guest->res_status,
    //        'res_no'                    =>$guest->res_no,
    //        'is_group'                  =>$guest->is_group,
    //        'group_code'                =>$guest->group_code,
    //        'is_dummy'                  =>$guest->is_dummy,
    //        'Is_PM'                     =>$guest->Is_PM,
    //        'is_cancel'                 =>$guest->is_cancel,
    //        'cancel_date'               =>$guest->cancel_date,
    //        'is_checked_in'             =>$guest->is_checked_in,
    //        'ref_no'                    =>$guest->ref_no,
    //        'is_online'                 =>$guest->is_online,
    //    ]);

    //    $guestDeleted = $guest->forceDelete();
    //   // dd($guestDeleted);
    //   } 

    return response()->json(['message' => __('Data Moved To History Successfully')]);
 
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
