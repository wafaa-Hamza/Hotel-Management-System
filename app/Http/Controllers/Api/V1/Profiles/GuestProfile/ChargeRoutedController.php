<?php

namespace App\Http\Controllers\Api\V1\Profiles\GuestProfile;

use App\helpers;
use Carbon\Carbon;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralCollection;
use App\Http\Requests\ChargeRoutedRequest;
use App\Http\Resources\ChargeRoutedResource;
use App\Http\Requests\chargeRoutwithoutStatusRequest;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryChargeRoutedInterface;

class ChargeRoutedController extends Controller
{
    use helpers;

    private $chargeRoutedInterface;
    public function __construct(RepositoryChargeRoutedInterface $chargeRoutedInterface)
    {
        $this->chargeRoutedInterface=$chargeRoutedInterface;
    }
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
    public function store(ChargeRoutedRequest $request)
    {
        $this->authorize('create-chargerouted');

       $chargeRouted = $this->chargeRoutedInterface->store($request);

       return $this->apiResponse(new GeneralCollection($chargeRouted,ChargeRoutedResource::class));
//     $date="2022-04-08";
//    // $date="2023-04-06";
//    // dd(Carbon::parse($date)->startOfMonth());
//     $test = Transaction::specificMonthToDate($date);
//     dd($test);

    }
    /**
     * Undocumented function
     *@apiName storechargeRoutwithoutStatus
     * @param chargeRoutwithoutStatusRequest $request
     * "guestID_from","window_id_from", "routingTo":[ {"ledgerID",""guestID_to"",""window_id_to""}]
     * @return data from charge Routed
     */
    public function storechargeRoutwithoutStatus(chargeRoutwithoutStatusRequest $request)
    {
        $this->authorize('create-chargerouted');

       $chargeRouted = $this->chargeRoutedInterface->destroy($request->guestID_from);
       $chargeRouted = $this->chargeRoutedInterface->store($request);

       return $this->apiResponse(new GeneralCollection($chargeRouted,ChargeRoutedResource::class));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $this->authorize('view-chargerouted');

        $chargeRouted = $this->chargeRoutedInterface->show($id);
        if($chargeRouted->first() == null){
            return $this->apiResponse(['message' => 'not found'],404);

        }else{
            return $this->apiResponse(new GeneralCollection($chargeRouted,ChargeRoutedResource::class));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ChargeRoutedRequest $request, $id)
    {
        $this->authorize('edit-chargerouted');

        $chargeRouted = $this->chargeRoutedInterface->update($request,$id);
        if($chargeRouted == null){
            return $this->apiResponse(['message' => 'not found'],404);

        }else{
            return $this->apiResponse(new GeneralCollection($chargeRouted,ChargeRoutedResource::class));
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
        $this->authorize('delete-chargerouted');

        $chargeRouted = $this->chargeRoutedInterface->destroy($id);

        if($chargeRouted == null){
            return $this->apiResponse(['message' => 'not found'],404);

        }else{
            return $this->apiResponse(['message' => 'deleted']);
        }
    }
}