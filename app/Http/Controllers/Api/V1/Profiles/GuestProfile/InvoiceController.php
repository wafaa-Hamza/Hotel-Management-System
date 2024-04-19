<?php

namespace App\Http\Controllers\Api\V1\Profiles\GuestProfile;

use App\helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\InvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\GuestProfileResource;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryInvoiceInterface;
use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;


class InvoiceController extends Controller
{
    use helpers;


    private $invoiceInterface;
    public function __construct(RepositoryInvoiceInterface $invoiceInterface)
    {
        $this->invoiceInterface = $invoiceInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-invoice');

        $invoice = $this->invoiceInterface->index();
        if ($invoice->first()) {
            return $this->apiResponse(new GeneralCollection($invoice, InvoiceResource::class));
        } else {
            return response()->json(['message' => 'not found'], 404);
        }
    }

    public function invoicePagination()
    {
        $this->authorize('view-invoice');

        $invoice = $this->invoiceInterface->invoicePagination();
        if ($invoice->first()) {
            return $this->apiResponse(new GeneralCollection($invoice, InvoiceResource::class));
        } else {
            return response()->json(['message' => 'not found'], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create-invoice');

        $invoiceRequest = ['guest_id' => 1, 'window_id' => 1];
        $invoice = $this->invoiceInterface->store($invoiceRequest);
        if ($invoice->first()) {
            return $this->apiResponse(new GeneralCollection($invoice, InvoiceResource::class));
        } else {
            return response()->json(['message' => 'not store'], 404);
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
    public function update(InvoiceRequest $request, $id)
    {
        $this->authorize('edit-invoice');

        // $invoiceRequest = ['guest_id' => 1, 'window_id' => 1];
        $invoice = $this->invoiceInterface->update($request, $id);
        if ($invoice) {
            return $this->apiResponse(new GeneralCollection($invoice, InvoiceResource::class));
        } else {
            return response()->json(['message' => 'not store'], 404);
        }
    }

    public function Generate_imageqrCode()
    {
        $displayQRCodeAsImage = GenerateQrCode::fromArray([
            new Seller('Salla'),
            new TaxNumber('1234567891'),
            new InvoiceDate('2021-07-12T14:25:09Z'),
            new InvoiceTaxAmount('15.00'),
            new InvoiceTotalAmount('115.00')

        ])->toBase64();
        return  $displayQRCodeAsImage;
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
