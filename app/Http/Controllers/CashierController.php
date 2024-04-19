<?php

namespace App\Http\Controllers;

use App\helpers;
use App\Models\Cashier;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class CashierController extends Controller
{
    use helpers;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        $this->authorize('view-cashier');

        return response()->json(Cashier::all());
    }

    public function getOpenCashier()
    {
        $hotelDate = $this->settings()->first()->hotel_date;
        $cachierOpen = Cashier::where('hotel_date', $hotelDate)->where('status',1)->with('users')->get();
        return $cachierOpen;
    }
    public function closeCashiers(Request $request)
    {
        $request->validate([
            'cashiersIDs'             => 'required|array|exists:cashiers,id',
        ]);
        $cachierClose = Cashier::whereIn('id',$request->cashiersIDs)->update([
            'status' => 0,
            'close' => now()
        ]);
      //  dd($cachierClose);
        if($cachierClose)
        {
            return true;
        }else{
            return false;
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function Cashier_Open()
    {
// $this->authorize('cashier-open');

        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;

        $cashierOpen = Cashier::where('user_id', auth()->user()->id)
            ->whereDate('hotel_date',  $hotelDate)->orderBy('open', 'desc')->first();
        if ($cashierOpen != null) {

            if (($cashierOpen->open != null) & ($cashierOpen->close == null)) {

                return "Cashier Is Already Open!!";
            }

            $cashier_open = Cashier::where('user_id', auth()->user()->id)
                ->whereDate('hotel_date', $hotelDate)->where('status', 1)->count();

            $cashier_close = Cashier::where('user_id', auth()->user()->id)->whereDate('hotel_date', $hotelDate)->where('status', 0)->count('no_of_opens');
            $cashier_closePlus = $cashier_close + 1;

        } else {
            $cashier_open = 0;
            $cashier_closePlus = 1;
        }
        if ($cashier_open > 0) {
            return  $cashier_open;
        } else {
            //dd('mm');
            try {

                $cashier = cashier::create([
                    'hotel_date'   =>  $hotelDate,
                    'user_id'      => auth()->user()->id,
                    'status'       => 1,
                    'open'         => now(),
                    'no_of_opens' => $cashier_closePlus,
                ]);
                return response()->json([
                    'message' => 'Cashier_Open Created Successfully',
                    'data' => $cashier,
                ], 201);
            } catch (ValidationException $e) {
                return response()->json([
                    'message' => 'Validation Error',
                    'errors' => $e->errors(),
                ], 400);
            }

    }
    }


    public function Cashier_Close()
    {

///  $this->authorize('cashier-close');
        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;

        $cashierClose = Cashier::where('user_id', auth()->user()->id)
            ->whereDate('hotel_date', $hotelDate)->orderBy('open', 'desc')->first();
        if ($cashierClose != null) {

            if ($cashierClose->close != null) {

                return "Cashier Is Already Close!!";
            }

            $cashier_close = Cashier::where('user_id', auth()->user()->id)
                ->whereDate('hotel_date', $hotelDate)->where('status', 1)->first();


            $cashier_close->update([
                'status'   => 0,
                'close'    => now(),

            ]);
        }
    }

        /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}