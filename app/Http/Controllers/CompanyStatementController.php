<?php

namespace App\Http\Controllers;

use App\Models\Statement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class CompanyStatementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-companystatment');

        return response()->json(Statement::all());
    }
    public function companySatatePagination()
    {
        $this->authorize('view-companystatment');

        return response()->json(Statement::paginate(request()->segment(count(request()->segments()))));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     
        try {
            $request->validate([
                'invoice_no'                 => 'required',
                'guest_id'                   => 'integer',
                'company_id'                 => 'integer|required',
                'payment_type_id'            => 'integer|nullable',
                'trans_date'                 => 'required|date_format:Y/m/d',
                'trans_no'                   => 'required',
                'trans_type'                 => 'required',
                'ref_no'                     => 'numeric|required',
                'description'                => 'required',
                'debit_amount'               => 'required',
                'credit_amount'              => 'required',
                'created_by'                 => 'integer',
                'void'                       => 'nullable',
                'void_datetime'              => 'nullable|after_or_equal:.date(Y-m-d H:i:s)',
                'paid_amount'                => 'nullable',
                'is_paid'                    => 'boolean|nullable',


            ]);

            $this->authorize('create-companystatment');

            $companyStatement = Statement::create([
                'invoice_no'         => $request->invoice_no,
                'guest_id'           => $request->guest_id,
                'company_id'         => $request->company_id,
                'payment_type_id'    => $request->payment_type_id,
                'trans_date'         => $request->trans_date,
                'trans_no'           => $request->trans_no,
                'trans_type'         => $request->trans_type,
                'ref_no'             => $request->ref_no,
                'description'        => $request->description,
                'debit_amount'       => $request->debit_amount,
                'credit_amount'      => $request->credit_amount,
                'created_by'         => $request->created_by,
                'void'               => $request->void,
                'void_datetime'      => $request->void_datetime,
                'paid_amount'        => $request->paid_amount,
                'is_paid'            => $request->is_paid,
                'guest_id'           => $request->guest_id,

            ]);

            return response()->json([
                'message' => 'Company-Statement Created Successfully',
                'data' => $companyStatement,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
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
        $this->authorize('view-companystatment');

        $companyStatement = Statement::find($id);
        return response()->json($companyStatement);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'invoice_no'                 => 'required',
            'guest_id'                   => 'integer|boolean',
            'company_id'                 => 'integer|required',
            'payment_type_id'            => 'integer|nullable',
            'trans_date'                 => 'required|date_format:Y/m/d',
            'trans_no'                   => 'required|string',
            'trans_type'                 => 'required',
            'ref_no'                     => 'numeric|required',
            'description'                => 'required',
            'debit_amount'               => 'required',
            'credit_amount'              => 'required',
            'created_by'                 => 'integer',
            'void'                       => 'nullable',
            'void_datetime'              => 'nullable|after_or_equal:.date(Y-m-d H:i:s)',
            'paid_amount'                => 'nullable',
            'is_paid'                    => 'boolean|nullable',


        ]);
        $this->authorize('edit-companystatment');

        $companyStatement = Statement::where('id', $id)->update([
            'invoice_no'         => $request->invoice_no,
            'guest_id'           => $request->guest_id,
            'company_id'         => $request->company_id,
            'payment_type_id'    => $request->payment_type_id,
            'trans_date'         => $request->trans_date,
            'trans_no'           => $request->trans_no,
            'trans_type'         => $request->trans_type,
            'ref_no'             => $request->ref_no,
            'description'        => $request->description,
            'debit_amount'       => $request->debit_amount,
            'credit_amount'      => $request->credit_amount,
            'created_by'         => $request->created_by,
            'void'               => $request->void,
            'void_datetime'      => $request->void_datetime,

        ]);
        $companyStatement = Statement::where('id', $id)->get();

        return response([

            'message' => 'Company-Statement Updated Successfully',
            'data' => $companyStatement,
        ], 200);
    }

    public function payment(Request $request)
    {

        try {

            $request->validate([

                'company_id'                 => 'required|integer',
                'payment_type_id'            => 'nullable|integer',
                'trans_date'                 => 'nullable|date',
                'trans_type'                 => 'nullable',
                'ref_no'                     => 'numeric|nullable',
                'description'                => 'required',
                'debit_amount'               => 'required',
                'credit_amount'              => 'required',
                'void'                       => 'nullable',
                'void_datetime'              => 'nullable|after_or_equal:.date(Y-m-d H:i:s)',
                'paid_amount'                => 'nullable',
                'is_paid'                    => 'nullable|boolean',
            ]);
            $this->authorize('create-payment');

            $trans_no_pay = Statement::where('trans_type', 'REC')->max('trans_no');
             //dd($request->trans_date);
            $payment = Statement::create([

                'invoice_no'         => $request->invoice_no,
                'guest_id'           => $request->guest_id,
                'company_id'         => $request->company_id,
                'payment_type_id'    => $request->payment_type_id,
                'trans_date'         => ($request->trans_date == null)?now():$request->trans_date,
                'trans_no'           => $trans_no_pay + 1,
                'trans_type'         => 'REC',
                'ref_no'             => $request->ref_no,
                'description'        => $request->description,
                'debit_amount'       => $request->debit_amount,
                'credit_amount'      => $request->credit_amount,
                'created_by'         => auth()->user()->id,
                'void'               => $request->void,
                'void_datetime'      => $request->void_datetime,
                'paid_amount'        => $request->paid_amount,
                'is_paid'            => $request->is_paid,

            ]);

            $invoice_col = $request->invoices;
            foreach ($invoice_col as $invoice) {

                $company_Statement = Statement::where('invoice_no', $invoice['invoice_no'])->first();

                if (($company_Statement->debit_amount) == $invoice['paid_amount']) {

                    $company_Statement->update([
                        'is_paid' => 1,
                        'paid_amount' => $invoice['paid_amount'],
                    ]);
                } elseif (($company_Statement->debit_amount) > $invoice['paid_amount']) {
                    if ($company_Statement->paid_amount > 0) {

                        if (($company_Statement->paid_amount + $invoice['paid_amount']) == $company_Statement->debit_amount) {
                            $company_Statement->update([
                                'is_paid' => 1,
                                'paid_amount' => $company_Statement->paid_amount + $invoice['paid_amount'],
                            ]);
                        } else {
                            //dd(($company_Statement->paid_amount + $invoice['paid_amount']));
                            $company_Statement->update([
                                'is_paid' => 0,
                                'paid_amount' => ($company_Statement->paid_amount + $invoice['paid_amount']),
                            ]);
                        }
                    } else {
                        $company_Statement->update([
                            'is_paid' => 0,
                            'paid_amount' => $invoice['paid_amount'],
                        ]);
                    }
                } elseif (($company_Statement->debit_amount) < $invoice['paid_amount']) {
                    $company_Statement->update([
                        'is_paid' => 2,
                        'paid_amount' => $invoice['paid_amount'],
                    ]);
                } else {
                    $company_Statement->update([
                        'paid_amount' => $invoice['paid_amount'],
                    ]);
                }
            }

            // if($credit_Note->invoice_no)
            return response()->json([
                'message' => 'Payment Created Successfully',
                'data' => $payment,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }

    public function AdvPayment(Request $request)
    {
        try {

            $request->validate([
                'company_id'                 => 'required|integer',
                'payment_type_id'            => 'required|integer',
                'trans_date'                 => 'required|date',
                'ref_no'                     => 'string|nullable',
                'description'                => 'nullable',
                'debit_amount'               => 'required',
                'credit_amount'              => 'required',
                'void'                       => 'nullable',
                'void_datetime'              => 'nullable|after_or_equal:.date(Y-m-d H:i:s)',
                'paid_amount'                => 'nullable|string',

            ]);
            $this->authorize('create-advpayment');

            $trans_no_AdvPay = Statement::where('trans_type', 'REC')->max('trans_no');

            $Adv_Payment = Statement::create([

                'invoice_no'         => $request->invoice_no,
                'guest_id'           => $request->guest_id,
                'company_id'         => $request->company_id,
                'payment_type_id'    => $request->payment_type_id,
                'trans_date'         => $request->trans_date,
                'trans_no'           => $trans_no_AdvPay + 1,
                'trans_type'         => 'REC',
                'ref_no'             => $request->ref_no,
                'description'        => $request->description,
                'debit_amount'       => $request->debit_amount,
                'credit_amount'      => $request->credit_amount,
                'created_by'         => auth()->user()->id,
                'void'               => $request->void,
                'void_datetime'      => $request->void_datetime,
                'paid_amount'        => $request->paid_amount,
                'is_paid'            => false,




            ]);
            return response()->json([
                'message' => 'Advanced Payment Created Successfully',
                'data' => $Adv_Payment,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }

    public function creditNote(Request $request)
    {
        try {

            $request->validate([

                'company_id'                 => 'required|integer',
                'payment_type_id'            => 'nullable|integer',
                'trans_date'                 => 'required|date',

                'ref_no'                     => 'string|nullable',
                'description'                => 'nullable',
                'debit_amount'               => 'required',
                'credit_amount'              =>  'required',
                'void'                       => 'nullable',
                'void_datetime'              => 'nullable|after_or_equal:.date(Y-m-d H:i:s)',

            ]);
            $this->authorize('create-creditNote');

            $trans_credit_Note = Statement::where('trans_type', 'CRN')->max('trans_no');

            $credit_Note = Statement::create([

                'invoice_no'         => $request->invoice_no,
                'guest_id'           => $request->guest_id,
                'company_id'         => $request->company_id,
                'trans_date'         => $request->trans_date,
                'trans_no'           =>  $trans_credit_Note + 1,
                'trans_type'         => 'CRN',
                'ref_no'             => $request->ref_no,
                'description'        => $request->description,
                'debit_amount'       => $request->debit_amount,
                'credit_amount'      => $request->credit_amount,
                'created_by'         => auth()->user()->id,
                'void'               => $request->void,
                'void_datetime'      => $request->void_datetime,


            ]);

            $invoice_col = $request->invoices;
            foreach ($invoice_col as $invoice) {

                $company_Statement = Statement::where('invoice_no', $invoice['invoice_no'])->first();
                //dd($company_Statement->debit_amount);

                if (($company_Statement->debit_amount) == $invoice['Paid_amount']) {

                    $company_Statement->update([
                        'is_paid' => 1,
                        'paid_amount' => $invoice['Paid_amount'],
                    ]);
                } elseif (($company_Statement->debit_amount) > $invoice['Paid_amount']) {
                    if ($company_Statement->paid_amount > 0) {

                        if (($company_Statement->paid_amount + $invoice['Paid_amount']) == $company_Statement->debit_amount) {
                            $company_Statement->update([
                                'is_paid' => 1,
                                'paid_amount' => $company_Statement->paid_amount + $invoice['Paid_amount'],
                            ]);
                        } else {

                            $company_Statement->update([
                                'is_paid' => 0,
                                'paid_amount' => ($company_Statement->paid_amount + $invoice['Paid_amount']),
                            ]);
                        }
                    } else {
                        $company_Statement->update([
                            'is_paid' => 0,
                            'paid_amount' => $invoice['Paid_amount'],
                        ]);
                    }
                } elseif (($company_Statement->debit_amount) < $invoice['Paid_amount']) {
                    $company_Statement->update([
                        'is_paid' => 2,
                        'paid_amount' => $invoice['Paid_amount'],
                    ]);
                } else {
                    $company_Statement->update([
                        'paid_amount' => $invoice['Paid_amount'],
                    ]);
                }
            }

            return response()->json([
                'message' => 'Credit-Note Created Successfully',
                'data' => $credit_Note,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }

    public function debitNote(Request $request)
    {
        try {

            $request->validate([
                'company_id'                 => 'required|integer',
                'trans_date'                 => 'required|date',
                'trans_type'                 => 'required',
                'ref_no'                     => 'numeric|required',
                'description'                => 'required',
                'debit_amount'               => 'required',
                'credit_amount'              =>  'required',
                'void'                       => 'nullable',
                'void_datetime'              => 'nullable|after_or_equal:.date(Y-m-d H:i:s)',
                'paid_amount'                => 'nullable',
                'is_paid'                    => 'nullable',
            ]);
            $this->authorize('create-debitNote');

            $trans_debit_Note = Statement::where('trans_type', 'DRN')->max('trans_no');

            $Debit_Note = Statement::create([

                'invoice_no'         => $request->invoice_no,
                'guest_id'           => $request->guest_id,
                'company_id'         => $request->company_id,
                'trans_date'         => $request->trans_date,
                'trans_no'           => $trans_debit_Note + 1,
                'trans_type'         => 'DRN',
                'ref_no'             => $request->ref_no,
                'description'        => $request->description,
                'debit_amount'       => $request->debit_amount,
                'credit_amount'      => $request->credit_amount,
                'created_by'         => auth()->user()->id,
                'void'               => $request->void,
                'void_datetime'      => $request->void_datetime,
                'paid_amount'        => $request->paid_amount,
                'is_paid'            => $request->is_paid,

            ]);
            return response()->json([
                'message' => 'Debit-Note Created Successfully',
                'data' => $Debit_Note,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
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
        $this->authorize('delete-companystatment');

        $companyStatement = Statement::where('id', $id)->delete();
        return response()->json([
            'status' => true,
            'message' => 'Company-Statement Deleted Successfully',
        ], 201);
    }

    public function void($id)
    {
        $this->authorize('create-companystatment');

        $void = Statement::where('id', $id)->first();
        $now = Carbon::now();
        $void = Statement::create([

            'invoice_no'         => $void->invoice_no,
            'guest_id'           => $void->guest_id,
            'company_id'         => $void->company_id,
            'payment_type_id'    => $void->payment_type_id,
            'trans_date'         => $void->trans_date,
            'trans_no'           => $void->trans_no,
            'trans_type'         => $void->trans_type,
            'ref_no'             => $void->ref_no,
            'description'        => $void->description,
            'debit_amount'       => $void->debit_amount * -1,
            'credit_amount'      => $void->credit_amount * -1,
            'created_by'         => $void->created_by,
            'void'               => $void->void,
            'void_datetime'      => $void->void_datetime,
            'paid_amount'        => $void->paid_amount,
            'is_paid'            => $void->is_paid,

        ]);
        $void->update([
            'void' => 1,
            'void_datetime' => $now,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'void Created Successfully',
        ], 201);
    }

    public function CompanyStatement_ByDates(Request $request)
    {
        $this->authorize('view-companyStatementbydates');

        $companyARStatement = Statement::with('companyProfile', 'users')
            ->whereBetween('trans_date', [$request->startDate, $request->endDate])
            ->where('company_id', $request->company_id)
            ->get();
        return response()->json(['CompanyARStatement' => $companyARStatement]);
    }
    public function CompanyInvoices(Request $request)
    {
        $this->authorize('create-companyinvoices');

        $invoices = Statement::with('companyProfile')->where('company_id', $request->company_id)->selectRaw('*, (debit_amount - paid_amount) as balance')
            ->where(function ($query) {
                $query->whereRaw('debit_amount - paid_amount > 0');
            })
            ->get();
        return response()->json($invoices);
    }
}
