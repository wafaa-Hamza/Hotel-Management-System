<?php

namespace App\Http\Controllers;

use App\Models\Guests;
use App\Models\ExtendStay;
use App\Models\ResRateDay;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryPreChargeInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestInhouseInterface;
use App\Repositoryinterface\RepositoryTaskDetailsinterface;
use Carbon\Carbon;

class ExtendStayController extends Controller
{
    private $repositoryGuestInhouseInterface;
    private $preChargeInterface;
    private $taskDetailInterface;

    public function __construct(
        RepositoryGuestInhouseInterface $repositoryGuestInhouseInterface,
        RepositoryPreChargeInterface $preChargeInterface,
        RepositoryTaskDetailsinterface $taskDetailInterface
    ) {
        $this->repositoryGuestInhouseInterface = $repositoryGuestInhouseInterface;
        $this->preChargeInterface = $preChargeInterface;
        $this->taskDetailInterface = $taskDetailInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-extendstay');

        return response()->json(ExtendStay::all());
    }

    public function addResRateDaysToNewOUtDate($request)
    {
        $newOutDate = Carbon::parse($request->out_date_to)->subDay();
        $oldOutDate = Carbon::parse($request->out_date_from);
        $lastResRatDaysForGuest = ResRateDay::where('guest_id', $request->guest_id)->orderBy('day_date', 'desc')->first();
        if ($newOutDate <  $oldOutDate) {
            $newOutDate = Carbon::parse($request->out_date_to);
            for ($date = $newOutDate; $date->lte($oldOutDate); $date->addDay()) {
                $lastResRatDaysForGuest = ResRateDay::where('guest_id', $request->guest_id)->whereDate('day_date', $date)->delete();
            }

            $getPrechargeData = $this->preChargeInterface->getPreChargeDAtaForGest($request->guest_id);
            $guestInhouseAddPrecharge = $this->preChargeInterface->destroy($request->guest_id);
            $guestInhouseAddPrecharge = $this->preChargeInterface->preChargeStore($getPrechargeData);
            return;
        }

        if ($lastResRatDaysForGuest) {
            $storeResRateDayRequest = new Request;
            $storeResRateDayRequest['res_rate_days'] = [$lastResRatDaysForGuest->toArray()];
            for ($date = $oldOutDate; $date->lte($newOutDate); $date->addDay()) {
                $resRateDays = $storeResRateDayRequest->input('res_rate_days');
                $resRateDays[0]['day_date'] = $date;
                $storeResRateDayRequest->merge(['res_rate_days' => $resRateDays]);

                $this->repositoryGuestInhouseInterface->resRateDaysStore($storeResRateDayRequest, $request->guest_id);
            }
            $getPrechargeData = $this->preChargeInterface->getPreChargeDAtaForGest($request->guest_id);
            $guestInhouseAddPrecharge = $this->preChargeInterface->destroy($request->guest_id);
            $guestInhouseAddPrecharge = $this->preChargeInterface->preChargeStore($getPrechargeData);

            return 'done';

        }else{
        //guest_id , day_date , rm_rate , rate_id = in guest rate_code_id , meal_id , meal_package_id
            $lastResRatDaysForGuest = Guests::where('id',$request->guest_id)->select('id','rm_rate' ,'rate_code_id','meal_id','meal_package_id')->first();
            $lastResRatDaysForGuest['guest_id'] =  $lastResRatDaysForGuest->id;
            $lastResRatDaysForGuest['rate_id'] =  $lastResRatDaysForGuest->rate_code_id;
            $storeResRateDayRequest = new Request;
            $storeResRateDayRequest['res_rate_days'] = [$lastResRatDaysForGuest->toArray()];
              for($date = $oldOutDate; $date->lte($newOutDate); $date->addDay())
            {
               $resRateDays = $storeResRateDayRequest->input('res_rate_days');
               $resRateDays[0]['day_date'] = $date;
               $storeResRateDayRequest->merge(['res_rate_days' => $resRateDays]);
               
                $this->repositoryGuestInhouseInterface->resRateDaysStore($storeResRateDayRequest,$request->guest_id);
            }
            $getPrechargeData = $this->preChargeInterface->getPreChargeDAtaForGest($request->guest_id);
            $guestInhouseAddPrecharge = $this->preChargeInterface->destroy($request->guest_id);
            $guestInhouseAddPrecharge = $this->preChargeInterface->preChargeStore($getPrechargeData);
            
            return 'done';

        }
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
                'guest_id'                     => 'required|max:254',
                'out_date_from'                => 'required|after_or_equal:.date(Y-m-d H:i:s)',
                'out_date_to'                  => 'required|after_or_equal:.date(Y-m-d H:i:s)',
                // 'done_by'                      =>'required|string|max:254',

            ]);
            $this->authorize('create-extendstay');

            $Extend_Stay = new ExtendStay();

            // $saveExtend=
            $Extend_Stay->guest_id                 = $request->guest_id;
            $Extend_Stay->out_date_from            = $request->out_date_from;
            $Extend_Stay->out_date_to              = $request->out_date_to;
            $Extend_Stay->done_by                  = (!empty(auth()->user()->id)) ? auth()->user()->id : 1;
            $Extend_Stay->save();

            $guest = Guests::find($request->guest_id);
            //dd($guest);
            if ($guest->checked_out == 0) {
                //dd(Carbon::parse($request->out_date_to)->diffInDays($guest->in_date));
                $guest->update([
                    'no_of_nights' => Carbon::parse($request->out_date_to)->diffInDays($guest->in_date),
                    'out_date'  => $request->out_date_to,
                ]);
                // return response(["message" =>("The operation was completed successfully")]);
                $this->addResRateDaysToNewOUtDate($request);
                $this->taskDetailInterface->publicRefreshTask('dueOut', 1, 'due_out');
            } else {

                return response(["message" => ("not found")]);
            };




            return response()->json([
                'message'  => 'Extend_Stay Created Successfully',
                'data'     => $Extend_Stay,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 400);
        }
    }


    public function Extend_Stay_Print(Request $request)
    {
        $this->authorize('create-extendstay');

        $extend_stayPrint = ExtendStay::where('id', $request->id)
            ->with(['guests.profile', 'guests.room', 'users'])
            ->get();

        return response()->json(['Extend_stayPrint' => $extend_stayPrint]);
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
