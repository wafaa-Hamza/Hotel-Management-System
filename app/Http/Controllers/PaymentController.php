<?php

namespace App\Http\Controllers;

use App\Models\Payment_type;
use App\Models\PaymentMode;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //        $this->authorize('view-payment');

        return response()->json(Payment_type::with('modes')->get());
    }
    public function getPaymentMode()
    {

        return response()->json(PaymentMode::all());
    }
    public function paymentPagination()
    {
        $this->authorize('view-payment');


        return response()->json(Payment_type::paginate(request()->segment(count(request()->segments()))));
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
                'code'                  =>       'integer',
                'name'                  =>       'required|string|max:255',
                'name_loc'              =>       'required|string|max:255',
                'type'                  =>       'string|required',
                'is_cash'               =>       'boolean|nullable',
                'commission_per'        =>       'numeric|nullable',
                'commission_amt'        =>       'numeric|nullable',
                'payment_modes_id'      =>       'nullable|integer',
                'scth_paymentId'        =>       'nullable|integer',
                'gl_account'        =>       'nullable',

            ]);

            // $this->authorize('create-payment');

            $Payment = Payment_type::with('modes')->Create([
                'code' => $request->code,
                'name' => $request->name,
                'name_loc' => $request->name_loc,
                'type' => $request->type,
                'is_cash' => $request->is_cash,
                'commission_per' => $request->commission_per,
                'commission_amt' => $request->commission_amt,
                'payment_modes_id' => $request->payment_modes_id,
                'scth_paymentId' => $request->scth_paymentId,
                'gl_account' => $request->gl_account,
            ]);



            return response()->json([
                'message' => 'Payment Created Successfully',
                'data' =>  $Payment,
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
     * @param  \App\Models\Payment_type $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment_type $payment)
    {
        //        $this->authorize('view-payment');

        $payment = Payment_type::find($payment->id);

        // $paymentTypes = Payment_type::where('id', $payment->id)->with('modes')->get();
        return response()->json($payment);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Payment_type  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        try {
            $request->validate([
                'code'                  =>       'integer',
                'name'                  =>       'required|string|max:255',
                'name_loc'              =>       'required|string|max:255',
                'type'                  =>       'string|required',
                'is_cash'               =>       'boolean|nullable',
                'commission_per'        =>       'numeric|nullable',
                'commission_amt'        =>       'numeric|nullable',
                'payment_modes_id'      =>       'nullable|integer',
                'scth_paymentId'        =>       'nullable|integer',
                'gl_account'        =>       'nullable',

            ]);
            //            $this->authorize('edit-payment');


            //  $this->authorize('edit-payment');
            $payment = Payment_type::where('id', $id)->update([

                'code'                 => $request->code,
                'name'                 => $request->name,
                'name_loc'             => $request->name_loc,
                'type'                 => $request->type,
                'is_cash'              => $request->is_cash,
                'commission_per'       => $request->commission_per,
                'commission_amt'       => $request->commission_amt,
                'payment_modes_id'     => $request->payment_modes_id,
                'scth_paymentId'       => $request->scth_paymentId,
                'gl_account'       => $request->gl_account,
            ]);



            return response()->json([
                'message'    => 'Payment updated Successfully',
                'data'       =>  $payment,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message'    => 'Validation Error',
                'errors'     => $e->errors(),
            ], 400);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Payment_type  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment_type $payment)
    {
        //        $this->authorize('delete-payment');

        $payment->delete();
        return response()->json([
            'message' => 'Payment Deleted Successfully',
        ], 201);
    }
}
