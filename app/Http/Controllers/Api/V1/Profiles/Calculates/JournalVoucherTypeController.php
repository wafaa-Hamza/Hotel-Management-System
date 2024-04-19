<?php

namespace App\Http\Controllers\Api\V1\Profiles\Calculates;

use App\helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\JournalVoucherResource;
use App\Http\Requests\JournalVoucherTypeRequest;
use App\Repositoryinterface\Profiles\Calculate\RepositoryJournalVoucherTypeInterface;

class JournalVoucherTypeController extends Controller
{
    use helpers;
    private $jurnalVoucherTypeInterface;
    
    public function __construct(RepositoryJournalVoucherTypeInterface $jurnalVoucherTypeInterface)
    {
        $this->jurnalVoucherTypeInterface = $jurnalVoucherTypeInterface;
    } 
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        $this->authorize('view-journal voucher type');

        $jurnalVoucherType = $this->jurnalVoucherTypeInterface->index();
        if($jurnalVoucherType->first())
        {
         return $this->apiResponse(new GeneralCollection($jurnalVoucherType,JournalVoucherResource::class));
        }else{
         return response()->json(['message' => 'Not Found'],404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(JournalVoucherTypeRequest $request)
    {

//        $this->authorize('create-journal voucher type');


        $jurnalVoucherType = $this->jurnalVoucherTypeInterface->store($request);
        if($jurnalVoucherType)
        {
         return $this->apiResponse(new GeneralCollection($jurnalVoucherType,JournalVoucherResource::class));
 
        }else{
         return response()->json(['message' => 'Not Found'],404);
 
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
//        $this->authorize('view-journal voucher type');

        $jurnalVoucherType = $this->jurnalVoucherTypeInterface->show($id);
        if($jurnalVoucherType->first())
        {
            return $this->apiResponse(new GeneralCollection($jurnalVoucherType,JournalVoucherResource::class));

        }else{
         return response()->json(['message' => 'Not Found'],404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(JournalVoucherTypeRequest $request, string $id)
    {

//        $this->authorize('edit-journal voucher type');


        $jurnalVoucherType = $this->jurnalVoucherTypeInterface->update($request,$id);
        if($jurnalVoucherType->first())
        {
             return $this->apiResponse(new GeneralCollection($jurnalVoucherType,JournalVoucherResource::class));

 
        }else{
         return response()->json(['message' => 'Not Found'],404);
 
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

//        $this->authorize('delete-journal voucher type');


        $jurnalVoucherType = $this->jurnalVoucherTypeInterface->destroy($id);
        if($jurnalVoucherType != 'notExist')
        {
            return response()->json(['message' => 'Deleted'],200);
        }else{
         return response()->json(['message' => 'notFound'],404);
        }
    }
}
