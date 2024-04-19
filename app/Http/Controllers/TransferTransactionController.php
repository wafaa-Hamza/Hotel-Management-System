<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\TransferTransaction;
use Illuminate\Validation\ValidationException;
use Sabberworm\CSS\Settings;
use Spatie\Multitenancy\Models\Tenant;

class TransferTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-transfertransaction');

        $transferTransaction=TransferTransaction::all();

        return response()->json($transferTransaction);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
               // 'hotel_date'              =>'required|after_or_equal:.date(Y-m-d H:i:s)',
                //'sys_date'                =>'required|after_or_equal:.date(Y-m-d H:i:s)',
                'trans_id'                =>'required|integer',
                'trf_from_guest_id'       =>'required|integer',
                'trf_to_guest_id'         =>'required|integer',
                'trf_from_window_id'      =>'required|integer',
                'trf_to_window_id'        =>'required|integer',
                'user_id'                 =>'nullable|integer',
                'reason'                  =>'nullable|string',
                
            ]);
            $this->authorize('create-transfertransaction');

            $transferUpdate=Transaction::find($request->trans_id);
            //dd($ddd);
            $transferUpdate->update([
                            'guest_id'=>$request->trf_to_guest_id,
                            'window_id'=>$request->trf_to_window_id,
                            'tr_guest_id_from'=>$request->trf_from_guest_id,
                            'tr_window_id_from'=>$request->trf_from_window_id
                         ]);
                         $tenant_id = Tenant::current();
                         if($tenant_id)
                         {
                              $settings = cache('settings_'.$tenant_id->id);
                         }else{
                             $settings = Setting::first();
                         }
        $transfer_Transaction=TransferTransaction::create([
            'hotel_date'                   =>$settings->first()->hotel_date ,
            'sys_date'                     => now(),
            'trans_id'                     => $request->trans_id,
            'trf_from_guest_id'            => $request->trf_from_guest_id,
            'trf_to_guest_id'              => $request->trf_to_guest_id,
            'trf_from_window_id'           => $request->trf_from_window_id,
            'trf_to_window_id'             => $request->trf_to_window_id,
            'user_id'                      => auth()->id(),
            'reason'                       => $request->reason,
           
        ]);
        return response()->json([
            'message'  => 'Transfer_Transaction Created Successfully',
            'data' => $transfer_Transaction], 201);

             }catch(ValidationException $e){
                 return response()->json([
                'message' => 'Validation Error',
                'errors'  => $e->errors()],400);
                 }
    }

    public function TransferTransaction_Search_By_Date(Request $request)
    {
        $this->authorize('view-transfertransaction');

        $TransferTransaction_Search_By_Date=TransferTransaction::
        with('transactions','trf_from_guest','trf_to_guest','trf_from_window','trf_to_window','users')
      ->whereBetween('hotel_date',[$request->startDate,$request->endDate])
        ->get();
        return response()->json([
            'TransferTransaction_Search_By_Date'=>$TransferTransaction_Search_By_Date
            ]);
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