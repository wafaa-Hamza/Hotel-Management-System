<?php

namespace App\Http\Controllers\Api\V1\Profiles\GuestProfile;

use App\helpers;
use Illuminate\Http\Request;
use App\Http\Requests\ExtraRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ViewGardenRequest;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\ExtraBedAndMealResource;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryExtraBedAndMealInterface;

class ExtraBedAndMealController extends Controller
{
    use helpers;
    private $extraBedAndMealInteracef;
    public function __construct(RepositoryExtraBedAndMealInterface  $extraBedAndMealInteracef) {
        $this->extraBedAndMealInteracef = $extraBedAndMealInteracef;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        $this->authorize('view-extra');

        $extras = $this->extraBedAndMealInteracef->index();
        if ($extras->first()) {
            return $this->apiResponse(new GeneralCollection($extras, ExtraBedAndMealResource::class), 200);
        } else
            return $this->apiResponse(["message" => __("not found")]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ViewGardenRequest $request)
    {
//        $this->authorize('create-extra');

        $extras = $this->extraBedAndMealInteracef->store($request);
        return $this->apiResponse(new ExtraBedAndMealResource($extras), 201);
    }
    public function storeExtraqForGuest(ExtraRequest $request)
    {
        $extras = $this->extraBedAndMealInteracef->storeExtraqForGuest($request->data);
        return $this->apiResponse(['message' => 'extra created for Guest']);
    }
    public function storeExtraqForGuestToTransaction(Request $request)
    {
        $getextraDataForTransaction = $this->extraBedAndMealInteracef->storeExtraDataToTransaction($request);
        if($getextraDataForTransaction)
        {
            return true;
        }else{
            return false;
        }
        // return $getextraDataForTransaction;
        // return $this->apiResponse(['message' => 'extra created for Guest']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
//        $this->authorize('view-extra');

        $extras = $this->extraBedAndMealInteracef->show($id);

        if ($extras) {
            return $this->apiResponse(['data' => collect(new ExtraBedAndMealResource($extras))], 200);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    public function getExtraByGuestID($id)
    {
//        $this->authorize('view-extra');

        $extras = $this->extraBedAndMealInteracef->getExtraByGuestID($id);

        if ($extras) {
            return $this->apiResponse(['data' => collect(new ExtraBedAndMealResource($extras))], 200);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }
    public function getExteraOfCheckInGuest()
    {
        $this->authorize('view-extra');

        $getExteraOfCheckInGuest = $this->extraBedAndMealInteracef->getExteraOfCheckInGuest();

        if ($getExteraOfCheckInGuest) {
            return $this->apiResponse(['data' => collect(new ExtraBedAndMealResource($getExteraOfCheckInGuest))], 200);
        } else {
            return $this->apiResponse(["message" => __("not found")]);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(ViewGardenRequest $request, string $id)
    {
//        $this->authorize('edit-extra');

        $extras = $this->extraBedAndMealInteracef->update($request, $id);
        if ($extras) {
            return $this->apiResponse(['data' =>new ExtraBedAndMealResource ($extras)]);
        } else {
            return $this->apiResponse(["message" => __("not found")]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyExtraGuestPivot(string $id)
    {
    //        $this->authorize('delete-extra from guest');
    }

    public function destroy(string $id)
    {
    //        $this->authorize('delete-extra');

        $extras = $this->extraBedAndMealInteracef->destroy($id);

        if ($extras) {
            return $this->apiResponse(["message" => __("the extras Has Been Deleted")], 200);
        } else {
            return $this->apiResponse(['message' => __("not found")]);
        }
        
 
   }
}
