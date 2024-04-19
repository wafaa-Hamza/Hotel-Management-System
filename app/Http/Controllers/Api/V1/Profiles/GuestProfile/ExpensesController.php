<?php

namespace App\Http\Controllers\Api\V1\Profiles\GuestProfile;

use App\helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ExpensesRequest;
use App\Http\Resources\ExpensesResource;
use App\Http\Resources\GeneralCollection;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryExpensesInterface;

class ExpensesController extends Controller
{
    use helpers;
    private $expensesInterface;
    public function __construct(RepositoryExpensesInterface $expensesInterface)
    {
        $this->expensesInterface = $expensesInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-expenses');

        $expenses = $this->expensesInterface->index();
        if($expenses->first())
        {
             return $this->apiResponse(new GeneralCollection($expenses,ExpensesResource::class));
        }
 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpensesRequest $request)
    {
        $this->authorize('create-expenses');

        $expenses = $this->expensesInterface->store($request);

        if($expenses)
        {
             return $this->apiResponse(['data' => collect(new ExpensesResource($expenses))]);
        }else{
         return response()->json(['message' => 'notFound'],404);
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
        $this->authorize('view-expenses');

        $expenses = $this->expensesInterface->show($id);

        if($expenses)
        {
             return $this->apiResponse(['data' => new ExpensesResource($expenses)]);
        }else{
         return response()->json(['message' => 'notFound'],404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ExpensesRequest $request, $id)
    {
        $this->authorize('edit-expenses');

        $expenses = $this->expensesInterface->update($request,$id);

        if($expenses)
        {
            return $this->apiResponse(['data' => collect(new ExpensesResource($expenses))]);
        }else{
         return response()->json(['message' => 'notFound'],404);
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
        $this->authorize('delete-expenses');

        $expenses = $this->expensesInterface->destroy($id);

        if($expenses)
        {
            return response()->json(['message' => 'Deleted'],200);
        }else{
         return response()->json(['message' => 'notFound'],404);
        }
    }
}