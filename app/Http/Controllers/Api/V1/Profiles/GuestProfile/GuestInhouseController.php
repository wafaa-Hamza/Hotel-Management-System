<?php

namespace App\Http\Controllers\Api\V1\Profiles\GuestProfile;

use App\helpers;
use App\Models\Guests;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralCollection;
use App\Http\Requests\GuestInhouseRequest;
use App\Http\Resources\GuestInhouseResource;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryWindowInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestInhouseInterface;

class GuestInhouseController extends Controller
{
    use helpers;

    private $guestInhouseInterface;

    public function __construct(RepositoryGuestInhouseInterface $guestInhouseInterface)
    {
        $this->guestInhouseInterface = $guestInhouseInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-guest');

        $guestInhouse = $this->guestInhouseInterface->index();
        if ($guestInhouse->first()) {

            return $this->apiResponse(new GeneralCollection($guestInhouse, GuestInhouseResource::class), 200);
        }

        return $this->apiResponse(["message" => __("not found")], 404);
    }
    public function inhousPagination()
    {
        $this->authorize('view-guest');

        $guestInhouse = $this->guestInhouseInterface->inhousPagination();
        if ($guestInhouse->first()) {
            return $this->apiResponse(new GeneralCollection($guestInhouse, GuestInhouseResource::class), 200);
        }
        return $this->apiResponse(["message" => __("not found")], 404);
    }

    public function GuestsFilter(Request $request)
    {
        $Guests_Filter =  Guests::search($request->term, null, $request->mixedSearch, $request->spaceficSearch)
            ->with('profile')
            ->withSum(
                ['transactions' => function ($query) {
                    $query->where('is_void', false);
                }],
                'payment_amount'
            )


            ->withSum(
                ['transactions' => function ($query) {
                    $query->where('is_void', false);
                }],
                'charge_amount'
            )
            ->with('room')->where(function ($q) use ($request) {
                if ($request->has('InDate')) 
                {
                    if ($request->has('end_date')) {
                        if ($request->input('end_date') != null) {

                            $q->whereDate('in_date', '>=', $request->input('start_date'))
                                ->whereDate('in_date', '<=', $request->input('end_date'));
                        } else {
                            $q->whereDate('in_date', '=', $request->input('start_date'));
                        }
                    } else {

                        $q->whereDate(
                            'in_date',
                            '=',
                            $request->input('start_date')
                        );
                    }
                }


                if ($request->has('OutDate')) 
                {

                        if ($request->input('OutDate') != null) {
                            if(!empty($request->end_date)){
                                $q->whereDate('out_date', '>=', $request->input('start_date'))
                                ->whereDate('out_date', '<=', $request->input('end_date'));
                            }else{
                                $q->whereDate('out_date','=',$request->input('start_date'));
                            }
                            

                        }
                }

                if ($request->has('AllDate')) 
                {

                        if ($request->input('AllDate') != null) {
                            if(!empty($request->end_date)){
                                $q->whereDate('in_date', '>=', $request->input('start_date'))
                                ->whereDate('out_date', '<=', $request->input('end_date'));
                                 }else{
                                    $q->whereDate('in_date','=',$request->input('start_date'));
                                 }

                                }
                }

                // if ($request->start_date != null) {

                //     if ($request->has('end_date')) {
                //         if ($request->input('end_date') != null) {

                //             $q->whereDate('in_date', '>=', $request->input('start_date'))
                //                 ->whereDate('in_date', '<=', $request->input('end_date'));
                //         } else {
                //             $q->whereDate('in_date', '=', $request->input('start_date'));
                //         }
                //     } else {

                //         $q->whereDate(
                //             'in_date',
                //             '=',
                //             $request->input('start_date')
                //         );
                //     }
                // }

//***********************************************************end search by date */
               
        if ($request->has('rate_code_id') && !empty($request->rate_code_id)) {
                    $q->where('rate_code_id', $request->input('rate_code_id'));
                }
                if ($request->has('is_reservation')  && !empty($request->is_reservation)) {
                    $q->where('is_reservation',$request->input('is_reservation'));
                }
                if ($request->has('is_cancel')  && !empty($request->is_cancel)) {
                    $q->where('is_cancel', $request->input('is_cancel'));
                }
                if ($request->has('is_checked_in')  && !empty($request->is_checked_in)) {


                    $q->where('is_checked_in', $request->input('is_checked_in'));
                }
                if ($request->has('checked_out')  && !empty($request->checked_out)) {

                    $q->where('checked_out', ($request->input('checked_out') == false) ? 0 : 1);
                }
                if ($request->has('market_segment_id') && !empty($request->market_segment_id)) {

                    $q->where('market_segment_id', $request->input('market_segment_id'));
                }
                if ($request->has('company_id') && !empty($request->company_id)) {

                    $q->where('company_id', $request->input('company_id'));
                }
                if ($request->has('source_of_business_id') && !empty($request->source_of_business_id)) {

                    $q->where('source_of_business_id', $request->input('source_of_business_id'));
                }
                if ($request->has('room_type_id') && !empty($request->room_type_id)) {
                    $q->where('room_type_id', $request->input('room_type_id'));
                }
                if ($request->has('room_id') && !empty($request->room_id)) {
                    $q->where('room_id', $request->input('room_id'));
                }
                if (!empty($request->res_no_from)) {
                    $q->where('id', '>=', $request->input('res_no_from'))
                        ->where('id', '<=', $request->input('res_no_to'));
                }
                if ($request->has('ref_no') && !empty($request->input('ref_no')) ) {

                    $q->where('ref_no', 'LIKE', $request->input('ref_no') . '%');
                }
                if ($request->has('guest_name') && !empty($request->guest_name)) {
                    if ($request->input('guest_name') != null) {


                        $q->whereHas('profile', function ($q) use ($request) {
                            $q->where('first_name', 'LIKE', '%' . $request->input('guest_name') . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $request->input('guest_name') . '%');
                        });
                    }
                }
            })
            ->with(['transactions' => function ($qu3) {
                $qu3->where('is_void', 0);
            }])
            ->get();
           // return $Guests_Filter;
        $ids = $Guests_Filter->pluck('id');
        //return $ids;

        $return_arr = [];

        $totChargeAmount = transaction::whereIn('guest_id', $ids)->where('is_void', 0)
            ->sum('charge_amount');
        $totPaymentAmount = transaction::whereIn('guest_id', $ids)->where('is_void', 0)
            ->sum('payment_amount');

        array_push($return_arr, $Guests_Filter);
        array_push($return_arr,  $totChargeAmount);
        array_push($return_arr, $totPaymentAmount);
        return response()->json([
            'data' => [
                'Guests_Filter'           => $Guests_Filter->transform(function ($q){
                    $data = new GuestInhouseResource($q);
                    $data['transactions_sum_charge_amount'] = $q->transactions_sum_charge_amount;
                    $data['transactions_sum_payment_amount'] = $q->transactions_sum_payment_amount;
                    return $data;
                }),
                //'Guests_Filter'           => $Guests_Filter,
                'Total_Charge_Amount'     => $totChargeAmount,
                'Total_Payment_Amount'    => $totPaymentAmount
            ]

        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GuestInhouseRequest $request)
    {
        $this->authorize('create-guest');

        $guestInhouse = $this->guestInhouseInterface->store($request);

        return $this->apiResponse(new GeneralCollection($guestInhouse, GuestInhouseResource::class), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view-guest');

        $guestInhouse = $this->guestInhouseInterface->show($id);

        if ($guestInhouse) {
            return $this->apiResponse(['data' => collect(new GuestInhouseResource($guestInhouse)), 200]);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(GuestInhouseRequest $request, $id)
    {
        $this->authorize('edit-guest');

        $guestInhouse = $this->guestInhouseInterface->update($request, $id);
        if ($guestInhouse) {

            return $this->apiResponse(new GeneralCollection($guestInhouse, GuestInhouseResource::class), 200);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
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
