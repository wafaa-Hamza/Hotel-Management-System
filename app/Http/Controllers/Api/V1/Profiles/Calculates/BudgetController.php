<?php

namespace App\Http\Controllers\Api\V1\Profiles\Calculates;

use App\helpers;
use Illuminate\Http\Request;
use Ramsey\Collection\Collection;
use App\Http\Controllers\Controller;
use App\Http\Requests\BudgetRequest;
use App\Http\Resources\BudgetResource;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\BudgetForGetByYearResource;
use Illuminate\Support\Collection as SupportCollection;
use App\Repositoryinterface\Profiles\Calculate\RepositoryBudgetInterface;

class BudgetController extends Controller
{
    use helpers;
    private $budgetInterface;

    public function __construct(RepositoryBudgetInterface $budgetInterface)
    {
        $this->budgetInterface = $budgetInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-budget');

       $budgetData = $this->budgetInterface->index();
       if($budgetData->first())
       {
        return $this->apiResponse(new GeneralCollection($budgetData,BudgetResource::class));
       }else{
        return response()->json(['message' => 'Not Found'],404);
       }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BudgetRequest $request)
    {
        $this->authorize('create-budget');

       $budgetData = $this->budgetInterface->store($request);
       if($budgetData)
       {
        return $this->apiResponse(new GeneralCollection($budgetData,BudgetResource::class));

       }else{
        return response()->json(['message' => 'Not Found'],404);

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
        $this->authorize('view-budget');

        $budgetData = $this->budgetInterface->show($id);
        if($budgetData)
        {
       //     return $this->apiResponse(new GeneralCollection($budgetData['allBudget'],BudgetForGetByYearResource::class));

    return response()->json($budgetData,200);
        }else{
         return response()->json(['message' => 'Not Found'],404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(BudgetRequest $request)
    {
        $this->authorize('edit-budget');

        $budgetData = $this->budgetInterface->update($request);
        if($budgetData)
        {
             return $this->apiResponse(new GeneralCollection($budgetData,BudgetResource::class));

 
        }else{
         return response()->json(['message' => 'Not Found'],404);
 
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