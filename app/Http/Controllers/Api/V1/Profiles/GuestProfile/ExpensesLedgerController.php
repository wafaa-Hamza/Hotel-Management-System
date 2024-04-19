<?php

namespace App\Http\Controllers\Api\V1\Profiles\GuestProfile;

use App\helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralCollection;
use App\Http\Requests\ExpensesLedgerRequest;
use App\Http\Resources\ExpensesLedgerResource;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryExpensesLedgerInterface;

class ExpensesLedgerController extends Controller
{
    use helpers;
    private $expensesLedgerInterface;
    public function __construct(RepositoryExpensesLedgerInterface $expensesLedgerInterface)
    {
        $this->expensesLedgerInterface = $expensesLedgerInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $this->authorize('view-ledger expenses');

       $expensesLedger = $this->expensesLedgerInterface->index();
       if($expensesLedger->first())
       {
            return $this->apiResponse(new GeneralCollection($expensesLedger,ExpensesLedgerResource::class));
       }else{
        return response()->json(['message' => 'notFound'],404);
       }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ExpensesLedgerRequest $request)
    {
//        $this->authorize('create-ledger expenses');

        $expensesLedger = $this->expensesLedgerInterface->store($request);

        if($expensesLedger)
        {
             return $this->apiResponse(['data' => collect(new ExpensesLedgerResource($expensesLedger))]);
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
//        $this->authorize('view-ledger expenses');

        $expensesLedger = $this->expensesLedgerInterface->show($id);

        if($expensesLedger)
        {
             return $this->apiResponse(['data' => new ExpensesLedgerResource($expensesLedger)]);
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
    public function update(ExpensesLedgerRequest $request, $id)
    {
//        $this->authorize('edit-ledger expenses');

        $expensesLedger = $this->expensesLedgerInterface->update($request,$id);

        if($expensesLedger)
        {
            return $this->apiResponse(['data' => collect(new ExpensesLedgerResource($expensesLedger))]);
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
//        $this->authorize('delete-ledger expenses');

        $expensesLedger = $this->expensesLedgerInterface->destroy($id);

        if($expensesLedger)
        {
            return response()->json(['message' => 'Deleted'],200);
        }else{
         return response()->json(['message' => 'notFound'],404);
        }
    }
}
