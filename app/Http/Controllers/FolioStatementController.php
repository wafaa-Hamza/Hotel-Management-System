<?php

namespace App\Http\Controllers;

use App\Models\Guests;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FolioStatementController extends Controller
{


    public function Folio_Statement(Request $request)
    {

        // $this->authorize('create-foliostatement');



        // $return_arr = [];
        // // $transactionCollection = Transaction::whereHas('guest',function($q) use($request){
        // //     $q->where('id',$request->id);
        // // })->select('transaction_collection','id')
        // // ->groupBy('transaction_collection','id')->get();
        // $transactionCollection = Transaction::whereHas('guest',function($q) use($request){
        //     $q->where('id',$request->id);
        // })
        // ->with('ledger', 'payment_type')
        //     ->with('created_by')
        //     ->with('room')
        //     ->with('taxes')
        // ->get()->groupBy('transaction_collection');

        // dd($transactionCollection);
        // return $transactionCollection;
        $folioStatement = Guests::with(['profile', 'createdBy', 'company', 'marketSegment', 'sourceBusiness', 'room.room_type', 'rateCode', 'meal', 'meal_package'])->with([
            'window' => function ($q){
                $q->withSum('transactions', 'charge_amount');
                $q->withSum('transactions', 'payment_amount');
                $q->with(['transactions' => function ($q2) {
                    $q2->with('ledger', 'payment_type');
                    $q2->with('created_by');
                    $q2->with('room');
                    $q2->with('taxes');
                    $q2->with(['transCollection' => function($q3){
                        $q3->with('ledger', 'payment_type');
                        $q3->with('created_by');
                        $q3->with('room');
                        $q3->with('taxes');
                    }]);
                    $q2->where('transaction_collection' ,'=', null);
                }]);

            }
        ])->where('id', $request->id)->get();
//$transactionIDNotShow = [];

// $handelDataWithTransactionCollection = $folioStatement->map(function ($guest) use ($transactionCollection, $transactionIDNotShow) {
//     $guest->transactions->groupBy('transaction_collection')->each(function ($transactions) use ($transactionCollection, &$transactionIDNotShow) {
//         $transactions->transform(function ($transaction) use ($transactionCollection, $transactions, &$transactionIDNotShow) {

//         });
//     });

//     return $guest;
// });

        // return $folioStatement;

        //  ->toSql();
        // dd( $folioStatement);


        //        $folioStatement = Guests::with(['profile', 'createdBy', 'company', 'marketSegment', 'sourceBusiness', 'room.room_type', 'rateCode', 'meal', 'meal_package'])
        //            ->with([
        //                'window'
        //                => function ($q) {
        //                    $q->withSum('transactions', 'charge_amount');
        //                    $q->withSum('transactions', 'payment_amount');
        //                    $q->with('transactions.ledger');
        //                }
        //            ])->where('id', $request->guest_id)
        //            ->get();
        // $return_arr = [];

        $totChargeAmount = Transaction::where('guest_id', $request->id)->sum('charge_amount');
        $totPaymentAmount = Transaction::where('guest_id', $request->id)->sum('payment_amount');

        // array_push($return_arr,  $totChargeAmount);
        // array_push($return_arr, $totPaymentAmount);
        // array_push($return_arr, $folioStatement);
        return response()->json([
            'data' => [
                'FolioState'           => $folioStatement,
                'Total_Charge_Amount'  => $totChargeAmount,
                'Total_Payment_Amount' => $totPaymentAmount
            ]

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
}
