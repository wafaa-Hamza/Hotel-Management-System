<?php

namespace App\Http\Controllers\Api\V1\Profiles\GuestProfile;

use App\helpers;
use App\Models\IdType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralCollection;
use App\Http\Requests\GuestProfileRequest;
use App\Http\Resources\GuestProfileResource;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestProfileInterface;
use App\models\guest_profile;

class GuestProfileController extends Controller
{
    use helpers;

    private $guestProfileInterface;

    public function __construct(RepositoryGuestProfileInterface $guestProfileInterface)
    {
        $this->guestProfileInterface = $guestProfileInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //        $this->authorize('view-profile');

        $guestsProfile = $this->guestProfileInterface->index();

        if ($guestsProfile->first()) {
            return $this->apiResponse(new GeneralCollection($guestsProfile, GuestProfileResource::class), 200);
        } else
            return $this->apiResponse(["message" => __("not found")], 404);
    }

    public function getIdType()
    {
        $idTypes = IdType::All();
        return $this->apiResponse($idTypes, 200);

    }

    public function seachProfile(Request $request)
    {
        $guestsProfile = $this->guestProfileInterface->seachProfile($request);

        if ($guestsProfile->first()) {

            return $this->apiResponse(new GeneralCollection($guestsProfile, GuestProfileResource::class), 200);
        } else
            return $this->apiResponse(["message" => __("not found")], 404);
    }

    public function guest_profile_search(Request $requset)
    {
        //        $this->authorize('view-profile');

        $guestsProfile = $this->guestProfileInterface->guest_profile_search($requset);
        if ($guestsProfile->first()) {

            return $this->apiResponse(new GeneralCollection($guestsProfile, GuestProfileResource::class));
        } else
            return $this->apiResponse(["message" => __("not found")], 404);
    }
    public function profilePagination()
    {
        //        $this->authorize('view-profile');

        $guestsProfile = $this->guestProfileInterface->profilePagination();
        if ($guestsProfile->first()) {
            return $this->apiResponse(new GeneralCollection($guestsProfile, GuestProfileResource::class), 200);
        } else
            return $this->apiResponse(["message" => __("not found")], 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(GuestProfileRequest $request)
    {
        //        $this->authorize('create-profile');

        $guestsProfile = $this->guestProfileInterface->store($request);

        return $this->apiResponse(new GeneralCollection($guestsProfile, GuestProfileResource::class), 200);
    }
    public function store_from_landlord($request)
    {
        //        $this->authorize('create-profile');

        $guestsProfile = $this->guestProfileInterface->store_from_landlord($request);
        if ($guestsProfile) {
            return $this->apiResponse(['data' => new GuestProfileResource($guestsProfile)]);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
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
        //        $this->authorize('view-profile');

        $guestsProfile = $this->guestProfileInterface->show($id);

        if ($guestsProfile) {
            return $this->apiResponse(['data' => collect(new GuestProfileResource($guestsProfile))], 200);
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
    public function update(GuestProfileRequest $request, $id)
    {
        //        $this->authorize('edit-profile');

        $guestsProfile = $this->guestProfileInterface->update($request, $id);
        if ($guestsProfile) {

            return $this->apiResponse(new GeneralCollection($guestsProfile, GuestProfileResource::class), 200);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    public function store_to_landlord($request)
    {
        $guestsProfile = $this->guestProfileInterface->store_to_landlord($request);

        return $this->apiResponse($guestsProfile, 200);
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
