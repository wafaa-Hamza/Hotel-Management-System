<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use App\Models\Guests;
use App\Models\Setting;
use App\Models\Statement;
use App\Models\ExtendStay;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use PhpParser\Node\Stmt\Return_;
use Illuminate\Support\Facades\DB;

class DayCloseController extends Controller
{
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
        //
    }

    public  function getCountOfCompRooms(Request $request)
    {
        $this->authorize('view-dayclose');
        $count = Guests::where('way_of_payment', '=', $request->way_of_payment)
            ->where('is_reservation', '=', false)
            ->where('is_checked_in', '=', true)
            ->where('checked_out', '=', false)
            ->count();
        return response()->json($count);
    }

    public function Calc_Of_AR_Deposit_Payment(Request $request)
    {
        $this->authorize('create-dayclose');

        $hotelDate = Setting::select('hotel_date')->first()->hotel_date; // $Calc_Deposit_payment= Statement::where('void', 0)->where('trans_type',$request->trans_type)
        $Calc_AR_Deposit_payment = Statement::where('void', 0)->where('trans_type', "REC")
            ->where('trans_date', $hotelDate)
            ->sum('credit_amount');
        return response()->json([
            'message'   => 'calculations Done Successfully',
            'data'      => $Calc_AR_Deposit_payment
        ]);
    }

    public function Calc_Of_AR_Balance()
    {
        $this->authorize('create-dayclose');

        $total_AR_Balance = Statement::get()->sum(function ($q) {
            return $q->debit_amount - $q->credit_amount;
        });
        return response()->json([
            'message'  => 'calculations Done Successfully',
            'data'    => $total_AR_Balance
        ]);
    }

    public function Count_Of_Extended_Rooms(Request  $request)
    {
        $this->authorize('create-dayclose');

        $Date = Carbon::parse($request->hotel_date);
        $extended_rooms = ExtendStay::whereDate('created_at', $Date)->count();
        //dd($extended_rooms);
        return response()->json([

            'data' =>  $extended_rooms,
        ]);
    }
    public function Sum_Of_Extended_Pax(Request  $request)
    {
        $this->authorize('create-dayclose');

        $Date = Carbon::parse($request->hotel_date);

        $Sum_Of_ExtendStay_Pax = Guests::whereHas('extendStay', function ($q) use ($Date) {
            $q->whereDate('created_at', $Date);
        })->get()->sum('no_of_pax');
        return response()->json([
            'data' => $Sum_Of_ExtendStay_Pax
        ]);
    }
    public function Sum_Of_Total_Taxes(Request  $request)
    {
        $this->authorize('create-dayclose');

        $sumOfAmount = 0;

        $Total_Taxes = Transaction::with('taxes')->where('hotel_date', $request->date)->get();

        foreach ($Total_Taxes as $tax) {
            $taxesData = $tax->taxes;

            foreach ($taxesData as $taxData) {
                $pivot = $taxData->pivot->amount;
                $sumOfAmount = $sumOfAmount + $pivot;
            }
        }

        return response()->json([
            'data'  => $sumOfAmount
        ]);
    }
    public function Total_oF_Payment(Request  $request)
    {
        $this->authorize('create-dayclose');

        $Total_Payment = Transaction::where('hotel_date', $request->date)
            ->where('payment_amount', '>', 0)
            ->sum('payment_amount');
        return response()->json([
            'data' => $Total_Payment
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
