<?php

namespace App\Http\Controllers;

use App\Models\Guests;
use App\Models\Search;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('search-view');

        return response()->json(Search::all());
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

    public function search_guests(Request $request)
    {
        $this->authorize('search-guests');
        
        $req = $request->only(['mobile_no','guest_id','guest_name','room_id','ref_no','id_no','InDate','OutDate']);
        $hasFind=0;
        foreach ($req as $key => $value) {
            if($value != ''){
             
               $hasFind=1; 
               break;
            }
        }
       
        if ($hasFind==1){
        $Guests_Search = Guests::with(['profile.country', 'createdBy', 'room', 'roomType', 'rateCode', 'reservation_status', 'company', 'meal'])->where(function ($q) use ($request) {
            if ($request->has('mobile_no')) {
               
                if($request->input('mobile_no') != ''){

                    $q->whereHas('profile', function ($q) use ($request) {
                        $q->where('mobile', 'LIKE', '%' . $request->input('mobile_no') . '%');
                    });
                }
            }
            if ($request->has('guest_id')) {
                $q->where('id',  $request->input('guest_id'));
            }
            if ($request->has('guest_name')) {
                if ($request->input('guest_name') != ''){

                    $q->whereHas('profile', function ($q) use ($request) {
                        $q->where('first_name', 'LIKE', '%' . $request->input('guest_name') . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $request->input('guest_name') . '%');
                    });
                }
            }
            if ($request->has('room_id')) {
                $q->where('room_id', 'LIKE', $request->input('room_id') . '%');
            }
            if ($request->has('ref_no')) {
                if($request->input('ref_no') != ''){
                    $q->where('ref_no', 'LIKE', $request->input('ref_no') . '%');
                }
            }

            if ($request->has('id_no')) {
                $q->whereHas('profile', function ($q) use ($request) {
                    $q->where('id_no', 'LIKE', $request->input('id_no') . '%');
                });
            }
            if ($request->has('InDate')) {
                $q->whereDate('in_date', '>=', $request->input('start_date'))
                    ->whereDate('in_date', '<=', $request->input('end_date'));
            }

            if ($request->has('OutDate')) {
                $q->whereDate('out_date', '>=', $request->input('start_date'))
                    ->whereDate('out_date', '<=', $request->input('end_date'));
            }
        })->get();
        if($Guests_Search->count() > 0){
        return ($Guests_Search);
        }else{
            return response('not found',404);
        }
            } else {
                return response('not found',404);
            }
        
    }

    public function Reservation_Search(Request $request)
    {
        //   $this->authorize('search-reservation');

        $Reservation_Search = Guests::with('profile')
            ->with('room')->with('createdBy')->with('company')
            ->with('roomType')->with('marketSegment')
            ->with('sourceBusiness')
            ->with('reservation_status')
            ->where('group_code', '!=', null)
            ->where('is_reservation', 1)
            ->where('is_checked_in', 0)
            ->Where('room_type_id', 1)
            ->where(function ($q) use ($request) {

                if ($request->has('mobile_no')) {
                    $q->whereHas('profile', function ($q) use ($request) {
                        $q->where('mobile', 'LIKE', '%' . $request->input('mobile_no') . '%');
                    });
                }

                if ($request->has('guest_name')) {
                    $q->whereHas('profile', function ($q) use ($request) {
                        $q->where('first_name', 'LIKE', '%' . $request->input('guest_name') . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $request->input('guest_name') . '%');
                    });
                }
                if ($request->has('room_id')) {
                    $q->where('room_id', 'LIKE', $request->input('room_id') . '%');
                }

                if ($request->input('created_by') != '') {
                    $q->where('created_by', $request->input('created_by'));
                }

                if ($request->input('arrival_date_from') != '') {

                    $q->whereDate('in_date', '>=', $request->input('arrival_date_from'))
                        ->whereDate('in_date', '<=', $request->input('arrival_date_to'));
                }
                if ($request->input('created_date_from') != '') {

                    $q->whereDate('created_at', '>=', $request->input('created_date_from'))
                        ->whereDate('created_at', '<=', $request->input('created_date_to'));
                }

                if ($request->input('res_no_from') != '') {
                    $q->where('id', '>=', $request->input('res_no_from'))
                        ->where('id', '<=', $request->input('res_no_to'));
                }

                if ($request->input('ref_no') != '') {
                    $q->where('ref_no', 'LIKE', '%' . $request->input('ref_no') . '%');
                }

                if ($request->input('company_id') != '') {
                    $q->where('company_id', $request->input('company_id'));
                }

                if ($request->input('market_segment_id') != '') {
                    $q->where('market_segment_id', $request->input('market_segment_id'));
                }
                if ($request->input('res_status') != '') {
                    $res_status = [];
                    $res_status = $request->input('res_status');
                    $q->whereIn('res_status', $res_status);
                }

                if ($request->has('id_no')) {
                    $q->whereHas('profile', function ($q) use ($request) {
                        $q->where('id_no', 'LIKE', '%' . $request->input('id_no') . '%');
                    });
                }

                if ($request->input('country_id') != '') {
                    $q->whereHas('profile', function ($q) use ($request) {
                        $q->where('country_id', $request->input('country_id'));
                    });
                }
            })
            ->orWhere('group_code', '=', null)
            ->where('checked_out', $request->input('checked_out'))
            ->where(function ($q) use ($request) {

                if ($request->has('mobile_no')) {
                    $q->whereHas('profile', function ($q) use ($request) {
                        $q->where('mobile', 'LIKE', '%' . $request->input('mobile_no') . '%');
                    });
                }

                if ($request->has('guest_name')) {
                    $q->whereHas('profile', function ($q) use ($request) {
                        $q->where('first_name', 'LIKE', '%' . $request->input('guest_name') . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $request->input('guest_name') . '%');
                    });
                }
                if ($request->has('room_id')) {
                    $q->where('room_id', 'LIKE', $request->input('room_id') . '%');
                }

                if ($request->input('created_by') != '') {
                    $q->where('created_by', $request->input('created_by'));
                }

                if ($request->input('arrival_date_from') != '') {

                    $q->whereDate('in_date', '>=', $request->input('arrival_date_from'))
                        ->whereDate('in_date', '<=', $request->input('arrival_date_to'));
                }
                if ($request->input('created_date_from') != '') {

                    $q->whereDate('created_at', '>=', $request->input('created_date_from'))
                        ->whereDate('created_at', '<=', $request->input('created_date_to'));
                }

                if ($request->input('res_no_from') != '') {
                    $q->where('id', '>=', $request->input('res_no_from'))
                        ->where('id', '<=', $request->input('res_no_to'));
                }

                if ($request->input('ref_no') != '') {
                    $q->where('ref_no', 'LIKE', '%' . $request->input('ref_no') . '%');
                }

                if ($request->input('company_id') != '') {
                    $q->where('company_id', $request->input('company_id'));
                }

                if ($request->input('market_segment_id') != '') {
                    $q->where('market_segment_id', $request->input('market_segment_id'));
                }
                if ($request->input('res_status') != '') {
                    $res_status = [];
                    $res_status = $request->input('res_status');
                    $q->whereIn('res_status', $res_status);
                }

                if ($request->has('id_no')) {
                    $q->whereHas('profile', function ($q) use ($request) {
                        $q->where('id_no', 'LIKE', '%' . $request->input('id_no') . '%');
                    });
                }

                if ($request->input('country_id') != '') {
                    $q->whereHas('profile', function ($q) use ($request) {
                        $q->where('country_id', $request->input('country_id'));
                    });
                }
            })
            ->with('moreName')->withCount('children')
            ->get();



        return $Reservation_Search;



        //return $Reservation_Search;
    }


    public function Return_Arrivals(Request $request)
    {
        $this->authorize('search-returnarrivals');

        $Return_Arrivals = Guests::with('profile')
            ->with('room')->with('createdBy')->with('company')
            ->with('roomTybe')->with('marketSegment')
            ->with('sourceBusiness')->where(function ($q) use ($request) {

                if ($request->has('start_date')) {
                    $q->whereDate('in_date', '>=', $request->input('start_date'))
                        ->whereDate('in_date', '<=', $request->input('end_date'));
                }
            })->get();
        return $Return_Arrivals;
    }

    public function Departures_Guests(Request $request)
    {
        $this->authorize('search-departuresguests');

        $Departures_Guests = Guests::with('profile')
            ->with('room')->with('createdBy')->with('company')
            ->with('roomTybe')->with('marketSegment')
            ->with('sourceBusiness')->where(function ($q) use ($request) {

                if ($request->has('start_date')) {
                    $q->whereDate('out_date', '>=', $request->input('start_date'))
                        ->whereDate('out_date', '<=', $request->input('end_date'));
                }
            })->get();
        return $Departures_Guests;
    }

    public function Return_Cancellation_Guests(Request $request)
    {
        $this->authorize('search-returncancellationguests');

        $Return_Cancellation_Guests = Guests::with('profile')->with('room')->with('createdBy')->with('company')
            ->with('roomTybe')->with('marketSegment')
            ->with('sourceBusiness')
            ->where('is_cancel', true)->where(function ($q) use ($request) {

                if ($request->has('cancel_date')) {
                    $q->whereDate('cancel_date', '>=', $request->input('start_date'))
                        ->whereDate('cancel_date', '<=', $request->input('end_date'));
                }
            })->get();
        return $Return_Cancellation_Guests;
    }


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
