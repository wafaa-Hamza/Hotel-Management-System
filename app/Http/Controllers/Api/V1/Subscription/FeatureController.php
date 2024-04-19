<?php

namespace App\Http\Controllers\Api\V1\Subscription;

use App\helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\FeatureRequest;

use App\Http\Resources\FeatureRsource;
use App\Http\Resources\GeneralCollection;
use App\Repositoryinterface\Repositoryfeatureinterface;

class FeatureController extends Controller
{
    use helpers;
    public $featureInterface;

    public function __construct(Repositoryfeatureinterface $RepositoryFeatureinterface)
    {
        $this->featureInterface = $RepositoryFeatureinterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-feature');

        $feature = $this->featureInterface->index();
if($feature->first()){
    return $this->apiResponse(new GeneralCollection($feature,FeatureRsource::class),200);

}else{
    return $this->apiResponse(["message" => __("not found")], 404);

}
    }
    public function featurePagination()
    {
        $this->authorize('view-feature');

        $feature = $this->featureInterface->featurePagination();
if($feature->first()){
    return $this->apiResponse(new GeneralCollection($feature,FeatureRsource::class),200);

}else{
    return $this->apiResponse(["message" => __("not found")], 404);

}
    }

    public function geSoftDeletedData()
    {
        $this->authorize('viewdelete-feature');

       $featureTrashed = $this->featureInterface->geSoftDeletedData();
       
       if($featureTrashed){
        return $this->apiResponse(new GeneralCollection($featureTrashed,FeatureRsource::class),200);
       }else{
        return $this->apiResponse(["message" => "not found"],404);
       }
    }

    public function restorDataTrashed($id)
    {
        $this->authorize('restoredelete-feature');

        $featureRestore = $this->featureInterface->restorDataTrashed($id);

        if ($featureRestore) {
            return $this->apiResponse(new GeneralCollection($featureRestore, FeatureRsource::class),200);
        } else {
            return $this->apiResponse(["message" => "not found"], 404);
        }
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FeatureRequest $request)
    {
        $this->authorize('create-feature');

        $feature = $this->featureInterface->store($request);

       return $this->apiResponse(new GeneralCollection($feature,FeatureRsource::class),200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(FeatureRequest $request, $id)
    {
        $this->authorize('edit-feature');


        $feature = $this->featureInterface->update($request,$id);
        if($feature){           
            return $this->apiResponse(new GeneralCollection($feature,FeatureRsource::class),200);
        }else{
            return $this->apiResponse(["message" => "not found"],404);
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
        $this->authorize('delete-feature');

        $feature = $this->featureInterface->destroy($id);
           
        if($feature){
            return $this->apiResponse(["message" => "the Feature Has Been Deleted"],200);
        }else{
            return $this->apiResponse(['message' => 'not found'], 404);
        }
    }

    public function DBDelete($id)
    {
        $this->authorize('forcedelete-feature');

        $feature = $this->featureInterface->DBDelete($id);
           
        if($feature){
            return $this->apiResponse(["message" => "the Feature Has Been Deleted from database"],200);
        }else{
            return $this->apiResponse(['message' => 'not found'], 404);
        }
    }
}
