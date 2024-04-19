<?php

namespace App\Http\Controllers;

use App\Models\Guests;
use App\Models\RateCode;
use App\Models\Ratechange;
use App\Models\Room_change_history;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestInhouseInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryPreChargeInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RateChangeHistoryController extends Controller
{
    private $guestInhouseInterface;
    private $preChargeInterface;
    public function __construct(RepositoryGuestInhouseInterface $guestInhouseInterface, RepositoryPreChargeInterface $preChargeInterface)
    {
        $this->guestInhouseInterface = $guestInhouseInterface;
        $this->preChargeInterface = $preChargeInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-ratechangehistory');

        return response()->json(Ratechange::all());
    }
    public function rateChangPagination()
    {
        $this->authorize('view-ratechangehistory');

        return response()->json(Ratechange::paginate(request()->segment(count(request()->segments()))));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        try {
            $request->validate([
                'hotel_date'                      => 'after_or_equal:.date(Y-m-d H:i:s)',
                'guest_id'                        => 'integer',
                'from_rate_code_id'               => 'integer',
                'to_rate_code_id'                 => 'integer',
                'from_rm_rate'                    => 'numeric',
                'to_rm_rate'                      => 'numeric',
                'room_id'                         => 'integer',
                'raeson'                          => 'string',

            ]);


            // $this->authorize('create-ratechangehistory');

            $ratechange = new Ratechange();
            $ratechange->hotel_date                = $request->hotel_date;
            $ratechange->guest_id                  = $request->guest_id;
            $ratechange->from_rate_code_id         = $request->from_rate_code_id;
            $ratechange->to_rate_code_id           = $request->to_rate_code_id;
            $ratechange->from_rm_rate              = $request->from_rm_rate;
            $ratechange->to_rm_rate                = $request->to_rm_rate;
            $ratechange->room_id                   = $request->guest_id;
            $ratechange->raeson                    = $request->raeson;
            $ratechange->created_by                = auth()->user()->id;

            $ratechange->save();
            // $ratechange= Ratechange::find($request->id);
            $guest = Guests::find($request->guest_id);

            if ($guest->is_checked_in == 1) {


                $guest->update([
                    'rate_code_id'  => $request->to_rate_code_id,
                    'rm_rate'       => $request->to_rm_rate
                ]);

                if ($request->has('res_rate_days') || $request->res_rate_days != null) {
                    $guestInhouseWithResRateDays = $this->guestInhouseInterface->resRateDaysDelete($request->guest_id);
                    //accept $request->res_rate_days as array of opject 
                    $guestInhouseWithResRateDays = $this->guestInhouseInterface->resRateDaysStore($request, $request->guest_id);

                    $getPrechargeData = $this->preChargeInterface->destroy($request->guest_id);
                    $getPrechargeData = $this->preChargeInterface->getPreChargeDAtaForGest($request->guest_id);
                    $guestInhouseAddPrecharge = $this->preChargeInterface->preChargeStore($getPrechargeData);
                }
            } else {
                return response(["message" => ("not found")]);
            };

            return response()->json([
                'message'  => 'Rate-Change Created Successfully',
                'data'     =>   $ratechange,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 400);
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view-ratechangehistory');

        $ratechange = Ratechange::find($id);
        return response()->json($ratechange);
    }


    public function Change_Rate_Print(Request $request)
    {
        $this->authorize('prent-ratechangehistory');

        $change_ratePrint = Ratechange::where('id', $request->id)
            ->with(['guest.profile', 'room', 'RateCodeFrom', 'RateCodeTo', 'users'])
            ->get();

        return response()->json(['Change_Rate_Print' => $change_ratePrint]);
    }


    public function Change_Room_Print(Request $request)
    {
        $this->authorize('prent-ratechangehistory');

        $change_roomPrint = Room_change_history::where('id', $request->id)
            ->with(['guest.profile', 'guest.room', 'RoomChangeFrom', 'RoomChangeTo', 'user'])
            ->get();

        return response()->json(['Change_Room_Print' => $change_roomPrint]);
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
    // public function destroy($id)
    // {
    //     $ratechange  =Ratechange::where('id',$id)->delete();
    //     return response()->json([
    //         'status'     => true,
    //         'message'    => 'Rate-change  Deleted Successfully',
    //     ],201);
    // }
}
