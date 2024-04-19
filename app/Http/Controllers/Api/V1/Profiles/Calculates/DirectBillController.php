<?php

namespace App\Http\Controllers\Api\V1\Profiles\Calculates;

use App\helpers;
use App\Http\Controllers\Controller;
use App\Repositoryinterface\Profiles\Calculate\RepositoryDirectBillInterface;
use Illuminate\Http\Request;

class DirectBillController extends Controller
{
    use helpers;
    private $directBillInterface;
    
    public function __construct(RepositoryDirectBillInterface $directBillInterface)
    {
        $this->directBillInterface = $directBillInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $directBillData = $this->directBillInterface->directBill($request);
        if($directBillData)
        {
            return $this->apiResponse(['data' => ['transaction_number' => $directBillData->original['transaction']->trans_no]]);

        }else{
            return $this->apiResponse(['message' => 'Direct Bill Not Completed']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}