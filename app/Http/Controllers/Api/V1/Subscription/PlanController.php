<?php

namespace App\Http\Controllers\Api\V1\Subscription;

use Exception;
use App\helpers;
use Illuminate\Http\Request;
use App\Http\Requests\PlanRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\Planresource;
use App\Http\Resources\GeneralCollection;
use App\Http\Requests\SubscriptionRequest;
use LucasDotVin\Soulbscription\Models\Plan;
use App\Http\Resources\SupscriptionResource;
use App\Repositoryinterface\Repositoryplaninterface;

class PlanController extends Controller
{
    use helpers;
    public $planInterface;

    public function __construct(Repositoryplaninterface $Repositoryplaninterface)
    {
        $this->planInterface = $Repositoryplaninterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-plan');

        $plans = $this->planInterface->index();
        if($plans->first()){
            return $this->apiResponse(new GeneralCollection($plans,Planresource::class),200);
        }else{
            return $this->apiResponse(["message" => __("not found")],404);
        }

    }
    public function planPagination()
    {
        $this->authorize('view-plan');

        $plans = $this->planInterface->planPagination();
        if($plans->first()){
            return $this->apiResponse(new GeneralCollection($plans,Planresource::class),200);
        }else{
            return $this->apiResponse(["message" => __("not found")],404);
        }

    }

    public function geSoftDeletedData()
    {
        $this->authorize('viewdeleted-plan');

       $planTrashed = $this->planInterface->geSoftDeletedData();
       
       if($planTrashed->first()){
        return $this->apiResponse(new GeneralCollection($planTrashed,Planresource::class),200);
       }else{
        return $this->apiResponse(["message" => "not found"],404);
       }
    }

    public function restorDataTrashed($id)
    {
        $this->authorize('restoredeleted-plan');

        $planRestore = $this->planInterface->restorDataTrashed($id);
       
    if($planRestore->first()){
        return $this->apiResponse(new GeneralCollection($planRestore,Planresource::class),200);
    }else{
        return $this->apiResponse(["message" => "not found"],404);
    }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PlanRequest $request)
    {
        $this->authorize('create-plan');

        $plans = $this->planInterface->store($request);
       
            $planID = $plans->first()->id;
           
        $plansFeature = $this->planInterface->createPlanFeature($request,$planID);

    return $this->apiResponse(new GeneralCollection($plans,Planresource::class),201);
    }

    public function createPlanForTenant(SubscriptionRequest $request)
    {
        //$this->authorize('create-plan');
        try{
            $planTenant = $this->planInterface->createPlanForTenant($request->tenantID,$request->planID,$request->expired_at,$request->started_at,$request->was_switched);
            if($planTenant)
            {
             return $this->apiResponse(new SupscriptionResource($planTenant));
            }else{
                return$this->apiResponse(['message' => 'Not Created']);
            }
        }catch(Exception $e){
            return$this->apiResponse(['message' => $e->getMessage()]);
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
        $this->authorize('view-plan');

        $plans = $this->planInterface->show($id);
       
    if($plans){
            
        return Planresource::make($plans);
    }else{
        return $this->apiResponse(["message" => "not found"],404);
    }
       
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PlanRequest $request, $id)
    {
        $this->authorize('edit-plan');

        $plans = $this->planInterface->update($request,$id);
        if($plans){
            
            return $this->apiResponse(new GeneralCollection($plans,Planresource::class),200);
        }else{
            return $this->apiResponse(["message" => "not found"],404);
        }

       
    }

    /**
     * Associating Plans with Features
     *
     * @param  int  $id,$request
     * @return \Illuminate\Http\Response
     */

     public function createPlanFeature(PlanRequest $request,$planID)
     {
        $this->authorize('create-plan');

        $planFeature = $this->planInterface->createPlanFeature($request,$planID);
      //  dd($planFeature);
        if($planFeature){
           // return $this->apiResponse(new Planresource($planFeature));
            return $this->apiResponse(new GeneralCollection($planFeature,Planresource::class),200);
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
        $this->authorize('delete-plan');

        $plan = $this->planInterface->destroy($id);
           
        if($plan){
            return $this->apiResponse(["message" => "the Plan Has Been Deleted"],200);
        }else{
            return $this->apiResponse(['message' => 'not found'], 404);
        }
    }

    public function DBDelete($id)
    {
        $this->authorize('forcedeleted-plan');

        $plan = $this->planInterface->DBDelete($id);
           
        if($plan){
            return $this->apiResponse(["message" => "the Plan Has Been Deleted from database"],200);
        }else{
            return $this->apiResponse(['message' => 'not found'], 404);
        }
    }

}
