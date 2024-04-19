<?php

namespace App\Http\Controllers;

use App\helpers;
use App\Models\Ledger;
use App\Models\Setting;
use App\Models\Transaction;
use App\Models\Payment_type;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\CalculationController;
use App\Repositoryinterface\RepositoryTaskDetailsinterface;

class TransactionController extends Controller
{
    use helpers;
    private $taskDetailsInterface;
    public function __construct(RepositoryTaskDetailsinterface $taskDetailsInterface)
    {
        $this->taskDetailsInterface = $taskDetailsInterface;
    }
    public function transaction_collection(Request $request)
    {
        Setting::find(1)->increment('transaction_collection');
    }
    public function calculate_excluded(Request $request)
    {
        $taxes = CalculationController::taxes($request->ledger_id, 0, $request->amount);
        return response()->json($taxes);
    }
    public function index()
    {
        $transactions = Transaction::with('created_by', 'updated_by', 'taxes')->get();
        return response()->json($transactions);
    }
    public function transactionPagination()
    {

        $transactions = Transaction::with('created_by', 'updated_by', 'taxes')->paginate(request()->segment(count(request()->segments())));
        return response()->json($transactions);
    }

    public function store(Request $request)
    {
        $tax = 1;
        try {
            $request->validate([
                'guest_id' => 'required|integer',
                'res_id' => 'nullable|integer',
                'room_id' => 'nullable|exists:rooms,id',
                'hotel_date' => 'nullable|date',
                'window_id' => 'required|exists:windows,id',
                'ledger_id' => 'nullable|exists:ledgers,id|prohibited_unless:payment_type_id,null|required_without_all:payment_type_id,payment_amount',
                'payment_type_id' => 'nullable|integer|prohibited_unless:ledger_id,null|required_without_all:ledger_id,charge_amount',
                'charge_amount' => 'nullable|numeric|prohibited_unless:payment_amount,null|required_without_all:payment_type_id,payment_amount',
                'payment_amount' => 'nullable|numeric|prohibited_unless:charge_amount,null|required_without_all:ledger_id,charge_amount',
                'trans_type' => 'required|string',
                'recd_type' => 'required|string',
                'ref_id' => 'nullable|integer',
                'description' => 'nullable|string',
                'is_reservation' => 'required|boolean',
                'invoice_id' => 'nullable|integer',
                'inc' => 'required|boolean',
                'voided_at' => 'nullable',
                'tr_guest_id_from' => 'nullable|exists:guests,id',
                'tr_window_id_from' => 'nullable|exists:windows,id',
            ]);

            $hotelDate = $this->settings()->first()->hotel_date;

            if ($request->ledger_id != null) {
                $ledger = Ledger::with('inctaxes', 'taxes')->where('id', $request->ledger_id)->first();
                if (count($ledger->inctaxes) == 0 && count($ledger->taxes) == 0) {
                    $tax = 0;
                } else {
                    $taxes = CalculationController::taxes($request->ledger_id, $request->inc, $request->charge_amount);
                    $inctaxes = $taxes->original->get('inctaxes');
                    foreach ($inctaxes as $inc) {
                        // Push sync values to the array for each item
                        $syncValues[$inc['id']] = [
                            'name' => $inc['name'],
                            'name_loc' => $inc['name_loc'],
                            'amount' => $ledger->type == 'Debit' ? $inc['value'] : $inc['value'] * -1,
                            'inc' => 1,
                        ];
                    }
                    $exctaxes = $taxes->original->get('exctaxes');
                    foreach ($exctaxes as $inc) {
                        $syncValues[$inc['id']] = [
                            'name' => $inc['name'],
                            'name_loc' => $inc['name_loc'],
                            'amount' => $ledger->type == 'Debit' ? $inc['value'] : $inc['value'] * -1,
                            'inc' => 0,
                        ];
                    }
                }

                $transaction_ledger = Transaction::where('ledger_id', $request->ledger_id)->max('trans_no');
                $charge_amount_after = $tax == 0 ? $request->charge_amount : ($request->inc == 1 ? $request->charge_amount : $taxes->original->get('total'));
                $charge_without_taxes_after = $tax == 0 ? $request->charge_amount : ($request->inc == 1 ? $taxes->original->get('total_with_tax') : $request->charge_amount);
                $transaction = Transaction::create([
                    'trans_no' => $transaction_ledger > 0 ? $transaction_ledger + 1 : 1,
                    'guest_id' => $request->guest_id,
                    'tr_guest_id_from' => $request->tr_guest_id_from,
                    'res_id' => $request->res_id,
                    'room_id' => $request->room_id,
                   // 'hotel_date' => $request->hotel_date,
                    'hotel_date' => $hotelDate,
                    'sys_date' => now(),
                    'ledger_id' => $request->ledger_id,
                    'payment_type_id' => null,
                    'charge_amount' => $ledger->type == 'Debit' ? $charge_amount_after : $charge_amount_after * -1,
                    'charge_without_taxes' => $ledger->type == 'Debit' ? $charge_without_taxes_after : $charge_without_taxes_after * -1,
                    'payment_amount' => null,
                    'window_id' => $request->window_id,
                    'tr_window_id_from' => $request->tr_window_id_from,
                    'trans_type' => $request->trans_type,
                    'recd_type' => $request->recd_type,
                    'ref_id' => $request->ref_id,
                    'description' => $request->description,
                    'is_reservation' => $request->is_reservation,
                    'is_void' => 0,
                    'invoice_id' => $request->invoice_id,
                    'inc' => $request->inc,
                    'transaction_collection' => $request->transaction_collection,
                    'voided_at' => $request->voided_at,
                    'created_by' => (!empty(auth()->user()->id)) ? auth()->user()->id : 1,
                ]);

                if ($tax == 1) {
                    $transaction->taxes()->sync($syncValues);
                }
            } else {
                $payment_type = Payment_type::where('id', $request->payment_type_id)->first();
                $transaction_payment_type = Transaction::where('payment_type_id', $request->payment_type_id)->max('trans_no');
                $transaction = Transaction::create([
                    'trans_no' => $transaction_payment_type > 0 ? $transaction_payment_type + 1 : 1,
                    'guest_id' => $request->guest_id,
                    'res_id' => $request->res_id,
                    'room_id' => $request->room_id,
                    'hotel_date' => $request->hotel_date,
                    'sys_date' => now(),
                    'ledger_id' => null,
                    'payment_type_id' => $request->payment_type_id,
                    'charge_amount' => null,
                    'charge_without_taxes' => null,
                    'payment_amount' => $payment_type->type == 'CR' ? $request->payment_amount : $request->payment_amount * -1,
                    'window_id' => $request->window_id,
                    'trans_type' => $request->trans_type,
                    'recd_type' => $request->recd_type,
                    'ref_id' => $request->ref_id,
                    'description' => $request->description,
                    'is_reservation' => $request->is_reservation,
                    'is_void' => 0,
                    'invoice_id' => $request->invoice_id,
                    'inc' => $request->inc,
                    'voided_at' => $request->voided_at,
                    'created_by' => auth()->user()->id,
                ]);
                //$this->taskDetailsInterface->paymentRequiredTaskRefresh();
                $this->taskDetailsInterface->publicRefreshTask('paymentRequiredTaskRefresh',1,'payment_required');
            }

            return response()->json(['transaction' => $transaction], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }
    public function test(Request $request)
    {
        $ledger = Ledger::with('inctaxes', 'taxes')->where('id', $request->ledger_id)->first();
    }
    public function show(Transaction $transaction)
    {
        return response()->json(['transaction' => $transaction]);
    }

    public function update(Request $request, Transaction $transaction)
    {
    }

    public function destroy(Transaction $transaction)
    {
        $tax = 1;
        try {
            if ($transaction->ledger_id != null) {
                $ledger = Ledger::with('inctaxes', 'taxes')->where('id', $transaction->ledger_id)->first();
                if (count($ledger->inctaxes) == 0 && count($ledger->taxes) == 0) {
                    $tax = 0;
                } else {
                    $taxes = CalculationController::taxes($transaction->ledger_id, $transaction->inc, $transaction->inc!=0?$transaction->charge_amount:$transaction->charge_without_taxes);
                    $inctaxes = $taxes->original->get('inctaxes');
                    foreach ($inctaxes as $inc) {
                        // Push sync values to the array for each item
                        $syncValues[$inc['id']] = [
                            'name' => $inc['name'],
                            'name_loc' => $inc['name_loc'],
                            'amount' => $inc['value'] * -1,
                            'inc' => 1,
                        ];
                    }
                    $exctaxes = $taxes->original->get('exctaxes');
                    foreach ($exctaxes as $inc) {
                        $syncValues[$inc['id']] = [
                            'name' => $inc['name'],
                            'name_loc' => $inc['name_loc'],
                            'amount' => $inc['value'] * -1,
                            'inc' => 0,
                        ];
                    }
                }
                $transaction_ledger = Transaction::where('ledger_id', $transaction->ledger_id)->max('trans_no');
                $NewTransaction = Transaction::create([
                    'trans_no' => $transaction_ledger > 0 ? $transaction_ledger + 1 : 1,
                    'guest_id' => $transaction->guest_id,
                    'tr_guest_id_from' => $transaction->tr_guest_id_from,
                    'res_id' => $transaction->res_id,
                    'room_id' => $transaction->room_id,
                    'hotel_date' => $transaction->hotel_date,
                    'sys_date' => $transaction->sys_date,
                    'ledger_id' => $transaction->ledger_id,
                    'payment_type_id' => null,
                    'charge_amount' => $tax == 0 ? $transaction->charge_amount * -1 : ($transaction->inc == 1 ? $transaction->charge_amount * -1 : $taxes->original->get('total') * -1),
                    'charge_without_taxes' => $tax == 0 ? $transaction->charge_without_taxes * -1 : ($transaction->inc == 1 ? $taxes->original->get('total_with_tax') * -1 : $transaction->charge_without_taxes * -1),
                    'payment_amount' => null,
                    'window_id' => $transaction->window_id,
                    'tr_window_id_from' => $transaction->tr_window_id_from,
                    'trans_type' => $transaction->trans_type,
                    'recd_type' => $transaction->recd_type,
                    'ref_id' => $transaction->ref_id,
                    'description' => $transaction->description,
                    'is_reservation' => $transaction->is_reservation,
                    'is_void' => 1,
                    'invoice_id' => $transaction->invoice_id,
                    'inc' => $transaction->inc,
                    'voided_at' => now(),
                    'created_by' => auth()->user()->id,
                ]);
                if ($tax == 1) {
                    $NewTransaction->taxes()->sync($syncValues);
                }
            } else {
                $transaction_payment_type = Transaction::where('payment_type_id', $transaction->payment_type_id)->max('trans_no');
                $NewTransaction = Transaction::create([
                    'trans_no' => $transaction_payment_type > 0 ? $transaction_payment_type + 1 : 1,
                    'guest_id' => $transaction->guest_id,
                    'tr_guest_id_from' => $transaction->tr_guest_id_from,
                    'res_id' => $transaction->res_id,
                    'room_id' => $transaction->room_id,
                    'hotel_date' => $transaction->hotel_date,
                    'sys_date' => $transaction->sys_date,
                    'ledger_id' => null,
                    'payment_type_id' => $transaction->payment_type_id,
                    'charge_amount' => null,
                    'charge_without_taxes' => null,
                    'payment_amount' => $transaction->payment_amount * -1,
                    'window_id' => $transaction->window_id,
                    'tr_window_id_from' => $transaction->tr_window_id_from,
                    'trans_type' => $transaction->trans_type,
                    'recd_type' => $transaction->recd_type,
                    'ref_id' => $transaction->ref_id,
                    'description' => $transaction->description,
                    'is_reservation' => $transaction->is_reservation,
                    'is_void' => 1,
                    'invoice_id' => $transaction->invoice_id,
                    'inc' => $transaction->inc,
                    'voided_at' => now(),
                    'created_by' => auth()->user()->id,
                ]);
            }
            $transaction->update([
                'is_void' => 1,
                'voided_at' => now(),
                'updated_by' => auth()->user()->id,
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }
}
