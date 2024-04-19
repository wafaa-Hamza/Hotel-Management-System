<?php

namespace App\Http\Controllers;

use App\Models\Guests;
use App\Models\LedgerCat;
use App\Models\preCharge;
use Illuminate\Http\Request;
use App\Models\DayCloseRecord;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Models\Room;

class GetFutureDateFunctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function  Number_of_Arrival(Request $request)
    {
        $this->authorize('view-futuredata');

        $FutureDate = Carbon::parse($request->input('date'));
//dd($FutureDate);
       $number_ofArrivals=Guests::whereDate('in_date','=',$FutureDate)
       ->count('is_checked_in');
       
        return  $number_ofArrivals;
    }
    public function  Number_of_Departures(Request $request)
    {
        $this->authorize('view-futuredata');

        $FutureDate = Carbon::parse($request->input('date'));
//dd($FutureDate);
       $Number_of_Departures=Guests::whereDate('out_date','=',$FutureDate)
       ->count('checked_out');
       
        return response()->json(['Number_of_Departures'=> $Number_of_Departures]);
    }

    public function Get_Room_Revenue(Request $request)
    {
        $this->authorize('view-futuredata');

        $FutureDate = Carbon::parse($request->input('date'));
         $room_revenue =preCharge::join('ledgers', 'ledgers.id', 'pre_charges.ledger_id')
       ->selectRaw('SUM(CASE WHEN ledgers.ledger_cat_id = "1"  THEN amount ELSE 0 END) AS revenue_amount') 
       ->whereDate('hotel_date','=',$FutureDate)->get();
       
    
      return $room_revenue;
    } 
    public function Get_FBRevenue(Request $request)
    {
        $this->authorize('view-futuredata');

        $FutureDate = Carbon::parse($request->input('date'));
         $FB_revenue =preCharge::join('ledgers', 'ledgers.id', 'pre_charges.ledger_id')
       ->selectRaw('SUM(CASE WHEN ledgers.ledger_cat_id = "2"  THEN amount ELSE 0 END) AS FB_amount') 
       ->whereDate('hotel_date','<=',$FutureDate)->get();
       
      return $FB_revenue;
    } 
    public function Get_Oc_Rooms(Request $request)
    {
        $this->authorize('view-futuredata');

        $FutureDate = Carbon::parse($request->input('date'));
         $Get_Oc_Rooms =Guests::whereDate('in_date', '<=',$FutureDate)
       ->whereDate('out_date','>=',$FutureDate)
       ->count();
       
      return $Get_Oc_Rooms;
    } 

    public function Get_Oc_Rooms_Percent(Request $request)
    {
        $this->authorize('view-futuredata');

        $countRooms=$this->Get_Oc_Rooms($request);
     
        $totalRooms = Room::where('rm_max_pax' ,'>', 0)->count();
     
        $Oc_Rooms_percent=($countRooms / $totalRooms)*100;

         return $Oc_Rooms_percent;
    } 



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
