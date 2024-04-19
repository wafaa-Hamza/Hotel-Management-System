<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\Ledger;
use App\Models\Setting;
use App\Models\closeSales;
use App\Models\Transaction;
use App\Models\Payment_type;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CloseSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view-closesalse');

        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;

        $Display_Data_With_Date = closeSales::whereDate('hotel_date', '>=', $request->startDate)
            ->whereDate('hotel_date', '<=', $request->endDate)
            ->get();
        return response()->json($Display_Data_With_Date);
    }


    public function StoreData_Of_Charge()
    {
       // dd(Artisan::getCurrentCommand());
        $this->authorize('create-dataofcharge');

        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;
        //$hotelDate = new DateTime('today');

        $Sys_Date = new DateTime('today');

        $total_Charge = $this->get_Total_Of_Charge_ByLedger($hotelDate);

        foreach ($total_Charge as $charge) {
            $Charge_Sales = closeSales::create([
                'ledger_id'          => $charge->ledger_id,
                'payment_type_id'    => 0,
                'hotel_date'         => $hotelDate,
                'sys_date'           => $Sys_Date,
                'charge_amount'      => $charge->total_charge_amount,
                'Payment_amount'     => 0

            ]);
        }
        //return  $Charge_Sales;

        return response()->json([
            'data' =>   $total_Charge,
        ]);
    }
    public function StoreData_Of_Payment()
    {
        //$this->authorize('create-dataofpayment');

        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;
        $Sys_Date = new DateTime('today');
        $Total_Amount = $this->get_Count_Of_payment($hotelDate);

        foreach ($Total_Amount as $Amount)
            $Amount_Sales = closeSales::create([

                'payment_type_id'        => $Amount->payment_type_id,
                'ledger_id'              => 0,
                'hotel_date'             => $hotelDate,
                'sys_date'               => $Sys_Date,
                'Payment_amount'         => $Amount->total_payment_amount,
                'charge_amount'          => 0

            ]);

        //  return $Amount_Sales;  
        return response()->json([
            'data' =>  $Total_Amount,
        ]);
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public static function get_Total_Of_Charge_ByLedger()
    {

        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;

        $Total_Charge = Transaction::with('ledger')
  
            ->whereDate('hotel_date', $hotelDate)
            ->where('ledger_id','!=',null)
            ->groupBy('ledger_id')
            ->selectRaw('sum(charge_amount) as total_charge_amount, ledger_id')
            ->get();

        return $Total_Charge;
    }

    public static function get_Count_Of_payment()
    {
        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;
        $Total_Amount = Transaction::with('payment_type')->whereDate('hotel_date', $hotelDate)
        ->where('payment_type_id','!=',null)
            ->select('payment_type_id', DB::raw('SUM(payment_amount) as total_payment_amount'))
            ->groupBy('payment_type_id')->get();

        return $Total_Amount;
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
