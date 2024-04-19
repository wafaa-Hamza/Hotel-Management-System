<?php

namespace App\Http\Controllers\Api\V1\Profiles\GuestProfile;

use App\helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiscrepancyRequest;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\DiscrepancyResource;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryDiscrepancyInterface;

class DiscrepancyController extends Controller
{
    use helpers;
    private $discrepancyInterface;
    
    public function __construct(RepositoryDiscrepancyInterface $discrepancyInterface)
    {
        $this->discrepancyInterface = $discrepancyInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view-discrepancy');

        $discrepancyData = $this->discrepancyInterface->index();
        if($discrepancyData->first())
        {
            return $this->apiResponse(new GeneralCollection($discrepancyData, DiscrepancyResource::class));
        }else{
            return $this->apiResponse(['message' => 'not found']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DiscrepancyRequest $request)
    {
        $this->authorize('create-discrepancy');

        $discrepancyData = $this->discrepancyInterface->store($request);
        if($discrepancyData )
        {
            return $this->apiResponse(['message' => 'created']);
        }else{
            return $this->apiResponse(['message' => 'not created']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->authorize('view-discrepancy');

        $discrepancyData = $this->discrepancyInterface->show($id);
        if($discrepancyData->first())
        {
            return $this->apiResponse(new GeneralCollection(collect($discrepancyData), DiscrepancyResource::class));
        }else{
            return $this->apiResponse(['message' => 'not found']);
        }
    }
    public function showById($id)
    {
        $this->authorize('view-discrepancy');

        $discrepancyData = $this->discrepancyInterface->show($id);
        if($discrepancyData->first())
        {
            return $this->apiResponse(new GeneralCollection(collect($discrepancyData), DiscrepancyResource::class));
        }else{
            return $this->apiResponse(['message' => 'not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DiscrepancyRequest $request,$id)
    {
        $this->authorize('edit-discrepancy');

        $discrepancyData = $this->discrepancyInterface->update($request,$id);
        if($discrepancyData)
        {
            return $this->apiResponse(['message' => 'updated']);
        }else{
            return $this->apiResponse(['message' => 'not found']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
