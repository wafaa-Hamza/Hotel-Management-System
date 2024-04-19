<?php

namespace App\Http\Controllers\Api\V1\Profiles\GuestProfile;

use App\helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\ChartAccountResource;
use App\Http\Requests\ChartOfAccountsRequest;
use App\Http\Requests\indexWithBalanceRequest;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryChartOfAccountsInterface;

class ChartOfAccountsController extends Controller
{
    use helpers;

    private $chartOfAccountsInterface;
    public function __construct(RepositoryChartOfAccountsInterface $chartOfAccountsInterface)
    {
        $this->chartOfAccountsInterface = $chartOfAccountsInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

//        $this->authorize('view-chart of account');


        $chartOfAccoubt = $this->chartOfAccountsInterface->index();
        $getAllLevel = $this->chartOfAccountsInterface->getAllLevel();
        if($chartOfAccoubt->first()){
            return $this->apiResponse(new GeneralCollection($chartOfAccoubt,ChartAccountResource::class),200);

        }else
        return $this->apiResponse(["message" => __("not found")],404);   
    }
    public function indexWithBalance(indexWithBalanceRequest $request)
    {
        $this->authorize('view-chart of account');

        $chartOfAccoubt = $this->chartOfAccountsInterface->indexWithBalance($request);
        if($chartOfAccoubt->first()){
            return $this->apiResponse(new GeneralCollection($chartOfAccoubt,ChartAccountResource::class));

        }else
        return $this->apiResponse(["message" => __("not found")]);   
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ChartOfAccountsRequest $request)
    {
//        $this->authorize('create-chart of account');

       $chartOfAccoubt = $this->chartOfAccountsInterface->store($request);
       if($chartOfAccoubt != 'level not found'){
       return $this->apiResponse(new GeneralCollection($chartOfAccoubt,ChartAccountResource::class));
       }else{
        return response()->json(['message' => 'level not found']);
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ChartOfAccountsRequest $request, $id)
    {
//       $this->authorize('edit-chart of account');

        $chartOfAccoubt = $this->chartOfAccountsInterface->update($request,$id);
       return $this->apiResponse(new GeneralCollection($chartOfAccoubt,ChartAccountResource::class));
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
