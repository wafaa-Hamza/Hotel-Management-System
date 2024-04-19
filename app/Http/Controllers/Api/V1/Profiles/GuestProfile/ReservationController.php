<?php

namespace App\Http\Controllers\Api\V1\Profiles\GuestProfile;

use Exception;
use App\helpers;
use Illuminate\Http\Request;
use App\Http\Requests\DateReqeust;
use App\Http\Controllers\Controller;
use App\Http\Requests\CancelRequest;
use App\Http\Requests\CheckOutRequest;
use App\Http\Requests\MoreNameRequest;
use App\Http\Resources\MoreNameResource;
use App\Http\Resources\GeneralCollection;
use App\Http\Requests\AvailabilityRequest;
use App\Http\Requests\GuestInhouseRequest;
use App\Http\Requests\GuestProfileRequest;
use App\Http\Resources\groupGuestResource;
use App\Http\Resources\GuestInhouseResource;
use App\Http\Resources\GuestProfileResource;
use App\Http\Requests\ConvertOnlyNameRequest;
use App\Http\Requests\GetOcGuestByDateRequest;
use App\Http\Requests\GroupReservationByIdsRequest;
use App\Http\Resources\showGroupRservisionForSelectedResource;
use App\Repositoryinterface\Integration\IntegrationForNTInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryPreChargeInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestInhouseInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestProfileInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryReservationChartInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryReservisionChartSummaryInterface;

class ReservationController extends Controller
{
    use helpers;

    private $guestInhouseInterface;
    private $guestProfileInterface;
    private $preChargeInterface;
    private $integrationForNTInterface;
    private $reservisionChartSummaryInterface;
    private $reservationChartInterface;

    public function __construct(
        RepositoryGuestInhouseInterface $guestInhouseInterface,
        RepositoryGuestProfileInterface $guestProfileInterface,
        RepositoryPreChargeInterface $preChargeInterface,
        IntegrationForNTInterface $integrationForNTInterface,
        RepositoryReservisionChartSummaryInterface $reservisionChartSummaryInterface,
        RepositoryReservationChartInterface $reservationChartInterface
    )  
    {
        $this->guestInhouseInterface = $guestInhouseInterface;
        $this->guestProfileInterface = $guestProfileInterface;
        $this->preChargeInterface = $preChargeInterface;
        $this->integrationForNTInterface = $integrationForNTInterface;
        $this->reservisionChartSummaryInterface = $reservisionChartSummaryInterface;
        $this->reservationChartInterface = $reservationChartInterface;
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
        } else

            return $this->apiResponse(["message" => __("not found")], 404);
    }

    public function GetOcGuestByDate(GetOcGuestByDateRequest $request)
    {
        $this->authorize('view-guest');

        $guestInhouse = $this->guestInhouseInterface->GetOcGuestByDate($request);
        if ($guestInhouse->first()) {

            return $this->apiResponse(new GeneralCollection($guestInhouse, GuestInhouseResource::class), 200);
        } else

            return $this->apiResponse(["message" => __("not found")], 404);
    }

    public function getGuestInhous(Request $request)
    {
        $this->authorize('view-guest');

        $guestInhouse = $this->guestInhouseInterface->getGuestInhous($request);
        if ($guestInhouse->first()) {

            return $this->apiResponse(new GeneralCollection($guestInhouse, GuestInhouseResource::class), 200);
        } else

            return $this->apiResponse(["message" => __("not found")], 404);
    }

    public function getGuestInhouswithDates(Request $request)
    {
        $this->authorize('view-guest');

        $guestInhouse = $this->guestInhouseInterface->getGuestInhouswithDates($request);

        if ($guestInhouse->first()) {

            return $this->apiResponse(new GeneralCollection($guestInhouse, GuestInhouseResource::class), 200);
        } else

            return $this->apiResponse(["message" => __("not found")], 404);
    }

    public function reservationPagination()
    {
        $this->authorize('view-guest');

        $guestInhouse = $this->guestInhouseInterface->inhousPagination();
        if ($guestInhouse->first()) {
            return $this->apiResponse(new GeneralCollection($guestInhouse, GuestInhouseResource::class), 200);
        } else
            return $this->apiResponse(["message" => __("not found")], 404);
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

        try{
            if ($request->onlyNameData) {
                return $this->storeOnlyName($request);
            } else {
                $request->is_reservation = 1;
                if ($request->room != null) {
                    $roomAvailablity = $this->guestInhouseInterface->availability($request);

                    if ($roomAvailablity['roomAvailableCount'] == 'NotAvailable') {
                        return $this->apiResponse(['message' => "thes Room ID Not Available"]);
                    }
                } else {
    
                    $roomAvailablity = $this->guestInhouseInterface->availability($request);

                    if ($roomAvailablity['roomAvailableCount'] == 'NotAvailable') {

                        return $this->apiResponse(['message' => "thes Room ID Not Available"]);
                    }
                }
                $guestInhouse = $this->guestInhouseInterface->store($request);

                return $guestInhouse;
    
                if ($request->moreNameData) {
                    $guestInhouse = $this->guestInhouseInterface->storeMoreName($request->moreNameData, ['guest_id' => $guestInhouse->first()->id]);
    
                    $guestId = $guestInhouse->first()->guest_id;
    
    
                    $guestInhouseWithResRateDays = $this->guestInhouseInterface->resRateDaysStore($request, $guestId);
                    $getPrechargeData = $this->preChargeInterface->getPreChargeDAtaForGest($guestId);
    
                    // dd($guestInhouse->first()->id);
    
                    $guestInhouseAddPrecharge = $this->preChargeInterface->preChargeStore($getPrechargeData);
                } else {
                    //dd( $guestInhouse->first()->id);
                    $guestInhouseWithResRateDays = $this->guestInhouseInterface->resRateDaysStore($request, $guestInhouse->first()->id);
                    $getPrechargeData = $this->preChargeInterface->getPreChargeDAtaForGest($guestInhouse->first()->id);
                    $guestInhouseAddPrecharge = $this->preChargeInterface->preChargeStore($getPrechargeData);
                    // dd($guestInhouseAddPrecharge);
                }
    
                // return $this->apiResponse(new GeneralCollection($guestInhouseWithResRateDays,GuestInhouseResource::class),201);
                // return $guestInhouseWithResRateDays;
                //$ntmp = $this->integrationForNTInterface->NTMPCreateOrUpdate($guestId);
                return $this->apiResponse(new GeneralCollection(collect($guestInhouseWithResRateDays), GuestInhouseResource::class), 201);
            }
        }catch(Exception $e){
            return $this->apiResponse(['message' => $e->getMessage()]);
        }

    }
    //start onlyName
    public function storeOnlyName(GuestInhouseRequest $request)
    {
        $this->authorize('create-guest');


        $request->is_reservation = 1;
        $request->profile_id = 1;
        $guestInhouse = $this->guestInhouseInterface->store($request);
        // dd($guestInhouse->first()->id);
        // $guestInhouse = $this->guestInhouseInterface->storeMoreName($request->moreName[0],$guestInhouse->first()->id);
        $guestId = $guestInhouse->first()->id;
        if(!$request->has('is_dumy'))
        {
            $guestInhouseWithResRateDays = $this->guestInhouseInterface->resRateDaysStore($request, $guestId);
            $getPrechargeData = $this->preChargeInterface->getPreChargeDAtaForGest($guestInhouse->first()->id);
            $guestInhouseAddPrecharge = $this->preChargeInterface->preChargeStore($getPrechargeData);
        }

        $arr = ['guest_id' => $guestId];
        if ($request->onlyNameData && $request->moreNameData) {
            $storeMorName = $this->guestInhouseInterface->storeOnlyAndMoreName($request, $arr);

            return $this->apiResponse(new GeneralCollection($guestInhouseWithResRateDays, GuestInhouseResource::class), 201);
        } else {
            $storeMorName = $this->guestInhouseInterface->storeMoreName($request, $arr);
            return $this->apiResponse(new GeneralCollection($guestInhouseWithResRateDays, GuestInhouseResource::class), 201);
        }
    }
    public function convertOnlyNameToNormalReservision(ConvertOnlyNameRequest $request)
    {
        $this->authorize('create-guest');

        // $deleteOnlyName = $this->guestInhouseInterface->deleteMoreName($request->guest_id);
        $deleteOnlyName = $this->guestInhouseInterface->deleteOnlyName($request->guest_id);
        if ($deleteOnlyName != null) {
            $updateOnlyName = $this->guestInhouseInterface->update($request, $request->guest_id);
            // dd($updateOnlyName);
            return $this->apiResponse(new GeneralCollection($updateOnlyName, GuestInhouseResource::class), 201);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }
    //end onlyName

    //Start group reservision
    public function isGroupProfileExist($request)
    {
        $this->authorize('edit-profile');

        $guestInhouse = $this->guestInhouseInterface->isGroupProfileExist($request);
        if ($guestInhouse) {
            $guestInhouse = $this->guestProfileInterface->update($guestInhouse);
        }
    }
    public function groupReservision(GuestInhouseRequest $request)
    {
        $this->authorize('create-guest');

        $request->is_reservation = 1;
        // $guestInhouse = $this->guestInhouseInterface->storeGroupProfile($request);
        $guestInhouse = $this->guestInhouseInterface->storeGroupGuest($request);
        // $guestInhouse = $this->guestInhouseInterface->store($request);
        // $guestId = $guestInhouse->first()->id;
        // $guestInhouseWithResRateDays = $this->guestInhouseInterface->resRateDaysStore($request,$guestId);
        if ($guestInhouse) {
            // return $this->apiResponse(new GeneralCollection($guestInhouse,GuestInhouseResource::class),201);
            return $this->apiResponse(["message" => __("Created succefully"), 'groupMasterID' => $guestInhouse], 201);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    public function updateGroupGuest(GuestInhouseRequest $request)
    {
        $this->authorize('edit-guest');


        $guestInhouse = $this->guestInhouseInterface->updateGroupGuest($request);
        if ($guestInhouse) {
            //  return "ok";
            return $this->apiResponse(["message" => __("Updated succefully")], 201);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    public function updateGroupGuestByIds(GroupReservationByIdsRequest $request)
    {
        // $this->authorize('edit-guest');


        $guestInhouse = $this->guestInhouseInterface->updateGroupGuestByIds($request);
        if ($guestInhouse) {
            //  return "ok";
            return $this->apiResponse(["message" => __("Updated succefully")], 201);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }
    public function guestAttachment(Request $request)
    {

        $this->authorize('create-guest');

        $guestInhouse = $this->guestInhouseInterface->guestAttachment($request);
        if ($guestInhouse) {
            return $this->apiResponse(["data" => $guestInhouse], 201);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    public function guestDeleteAttachment($id)
    {
        $this->authorize('delete-attachment guest');

        $guestInhouse = $this->guestInhouseInterface->guestDeleteAttachment($id);
        if ($guestInhouse) {
            return $this->apiResponse(["message" => __("file deleted")], 201);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    public function showGroupRservision($id)
    {

        $this->authorize('view-guest');

        $guestGroup =  $this->guestInhouseInterface->showGroupRservision($id);
        if (!is_null($guestGroup)) {
            //dd($this->apiResponse(new GeneralCollection( $guestGroup,groupGuestResource::class)));

            return $this->apiResponse(new GeneralCollection($guestGroup, groupGuestResource::class));
        } else {
            return $this->apiResponse(['message' => 'not found']);
        }
    }

    public function showGroupRservisionForSelected($id)
    {
        $this->authorize('view-guest');

        $guestGroup =  $this->guestInhouseInterface->showGroupRservision($id);
        if (!is_null($guestGroup)) {
            //dd($this->apiResponse(new GeneralCollection( $guestGroup,groupGuestResource::class)));

            return $this->apiResponse(new GeneralCollection($guestGroup, showGroupRservisionForSelectedResource::class));
            //return $this->apiResponse( $guestGroup);

        } else {
            return $this->apiResponse(['message' => 'not found']);
        }
    }

    public function storeGroupProfile(GuestProfileRequest $request)
    {
        // $this->authorize('create-profile');

        $guestGroup = $this->guestInhouseInterface->storeGroupProfile($request);

        return $this->apiResponse(new GeneralCollection($guestGroup, GuestProfileResource::class), 201);
    }
    //end group reservision

    public function storeMoreName(MoreNameRequest $request)
    {
        $this->authorize('create-guest');

        $storeMorName = $this->guestInhouseInterface->storeMoreName($request->data, null);
        return $this->apiResponse(new GeneralCollection($storeMorName, MoreNameResource::class), 201);
    }
    public function updateMoreName(MoreNameRequest $request, $guest_id)
    {
        $this->authorize('edit-guest');

        $deleteMoreName = $this->guestInhouseInterface->deleteMoreName($guest_id);
        if ($request->data != null) {
            $storeMoreName = $this->guestInhouseInterface->storeMoreName($request->data, $guest_id);
        }

        //  return $this->apiResponse(['data' => new MoreNameResource($storeMoreName)]);
        return $this->apiResponse(['data' => 'companions has been updated']);
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

            return $this->apiResponse(['data' => collect(new GuestInhouseResource($guestInhouse))], 200);
            // return $this->apiResponse(new GeneralCollection($guestInhouse->get(),GuestInhouseResource::class),201);

        } else {
            return $this->apiResponse(["message" => __("not found")]);
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
        //  $this->authorize('edit-guest');

        $request->is_reservation = 1;

        $guestInhouse = $this->guestInhouseInterface->update($request, $id);

        if ($guestInhouse) {
            if ($request->has('res_rate_days') || $request->res_rate_days != null) {
                $guestInhouseWithResRateDays = $this->guestInhouseInterface->resRateDaysDelete($id);

                $guestInhouseWithResRateDays = $this->guestInhouseInterface->resRateDaysStore($request, $id);

                $getPrechargeData = $this->preChargeInterface->destroy($id);
                $getPrechargeData = $this->preChargeInterface->getPreChargeDAtaForGest($id);
                $guestInhouseAddPrecharge = $this->preChargeInterface->preChargeStore($getPrechargeData);
            }



            return $this->apiResponse(new GeneralCollection($guestInhouse, GuestInhouseResource::class), 200);
        } else {
            return $this->apiResponse(["message" => __("not found or Update Locked")], 404);
        }
    }

    public function lockedReservision(GuestInhouseRequest $request, $id)
    {
        $this->authorize('locked-reservisionguest');

        $lockedReservisio = $this->guestInhouseInterface->lockedReservision($id, $request);
        if ($lockedReservisio) {
            return $this->apiResponse(["message" => __("locked updated")]);
        } else {
            return $this->apiResponse(["message" => __("not found")]);
        }
    }

    public function checkIn($id)
    {
     //   $this->authorize('checkin-guest');

        $request = ['is_reservation' => false, 'res_status' => 'CHK', 'is_checked_in' => true];
        // $guestInhouse = $this->guestInhouseInterface->update($request,$id);
        // $guestInhouse = $this->guestInhouseInterface->reservisionUpdate($request,$id);
        $guestInhouse = $this->guestInhouseInterface->isCheckedIn($id);
        if ($guestInhouse == 'NotAvailable') {
            return $this->apiResponse(["message" => __("not Available")], 201);
        }
        if ($guestInhouse) {

            // return $this->apiResponse(new GeneralCollection($guestInhouse,GuestInhouseResource::class),200);
            return $this->apiResponse(["message" => __("done")], 201);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    public function groupCheckIn(Request $requests)
    {
      //  $this->authorize('checkin-guest');

        $request = ['is_reservation' => false, 'res_status' => 'CHK', 'is_checked_in' => true];
        // $guestInhouse = $this->guestInhouseInterface->update($request,$id);
        // $guestInhouse = $this->guestInhouseInterface->reservisionUpdate($request,$id);
        $response = [];
        $count =0;
        foreach($requests->guestIDs as $id)
        {
            if($count == 0)
            {$guestInhouse = $this->guestInhouseInterface->payMasterCheckIn($id);}
            $count++;
            $guestInhouse = $this->guestInhouseInterface->isCheckedIn($id);
            $response[$id] = $guestInhouse;
        }

            return $this->apiResponse(["data" =>  $response], 201);
    }

    public function cancel(CancelRequest $request)
    {
        $this->authorize('cancel-guest');
        if ($request->groubeCode) {
            $guestInhouse = $this->guestInhouseInterface->groubeCancel($request);
        } else {
            $guestInhouse = $this->guestInhouseInterface->isCancel($request);
        }

        if ($guestInhouse) {

            return $this->apiResponse(new GeneralCollection($guestInhouse, GuestInhouseResource::class), 200);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    public function reInstate($id)
    {
        $this->authorize('reinstate-guest');

        $guestInhouse = $this->guestInhouseInterface->reInstate($id);
        if ($guestInhouse) {

            return $this->apiResponse(new GeneralCollection($guestInhouse, GuestInhouseResource::class), 200);
        } else {
            return $this->apiResponse(["message" => __("not found")]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function availability(AvailabilityRequest $request)
    {
        $this->authorize('create-guest');

        $avelableGuestreservition = $this->guestInhouseInterface->availability($request);

        return $this->apiResponse(['data' => [$avelableGuestreservition]], 200);
    }

    public function AvailabilityScreenData(AvailabilityRequest $request)
    {
        $this->authorize('create-guest');

        $availability =  $this->guestInhouseInterface->AvailabilityScreenData($request);
        return $this->apiResponse(['data' => $availability], 200);
    }

    public function destroy($id)
    {
        $this->authorize('delete-guest');

        $deleteGuest = $this->guestInhouseInterface->destroy($id);

        if ($deleteGuest) {
            return $this->apiResponse(['message' => 'Guest Deleted']);
        } else {
            return $this->apiResponse(['message' => 'Guest not Found'], 404);
        }
    }

    public function checkOut(CheckOutRequest $request)
    {
        $this->authorize('checkout-guest');

        $checkOutStatus = $this->guestInhouseInterface->checkOut($request);
        return $this->apiResponse(['data' => $checkOutStatus]);
    }

    public function cityLedgerCheckout(CheckOutRequest $request)
    {
        $this->authorize('checkout-guest');

        $cityLedgerCheckOutStatus = $this->guestInhouseInterface->cityLedgerCheckout($request);
        return $this->apiResponse(['data' => $cityLedgerCheckOutStatus]);
    }
    /**
     * Undocumented function
     *
     * @param Request $requet 
     * guest_id
     * res_rate_days[{}{}]
     * @return void
     */
    public function updateRateDays(Request $request)
    {
        if ($request->has('res_rate_days') || $request->res_rate_days != null) {
            $guestInhouseWithResRateDays = $this->guestInhouseInterface->resRateDaysDelete($request->guest_id);
            //accept $request->res_rate_days as array of opject 
            $guestInhouseWithResRateDays = $this->guestInhouseInterface->resRateDaysStore($request, $request->guest_id);

            $getPrechargeData = $this->preChargeInterface->destroy($request->guest_id);
            $getPrechargeData = $this->preChargeInterface->getPreChargeDAtaForGest($request->guest_id);
            $guestInhouseAddPrecharge = $this->preChargeInterface->preChargeStore($getPrechargeData);
            return $this->apiResponse(['mesage' => 'Updated']);
        } else {
            return $this->apiResponse(['mesage' => 'Not Updated']);
        }
    }

    public function reservisionChartSummary(DateReqeust $request)
    {
        $summary = $this->reservisionChartSummaryInterface->reservisionChartSummary($request);
        //return $summary;
        return $this->apiResponse(['data' => $summary]);
    }

    public function reservationChart(DateReqeust $request)
    {
      return  $this->reservationChartInterface->reservationChart($request);
    }
}
