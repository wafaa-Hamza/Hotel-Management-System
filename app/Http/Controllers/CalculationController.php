<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use Illuminate\Validation\ValidationException;

class CalculationController extends Controller
{
    public static function taxes($ledger_id, $tax_inc, $amount)
    {

        try {

            $ledger = Ledger::with('inctaxes', 'taxes')->where('id', $ledger_id)->first();
            if ($tax_inc == 0) {
                $total_with_taxes = collect(['total' => 0, 'total_tax_amount' => 0, 'total_with_tax' => 0, 'inctaxes' => [], 'total_inc' => 0, 'exctaxes' => [], 'total_exc' => 0]);
                $total = $amount;
                $total_tax = 0;
                $whole_tax = 0;
                $inctaxes_array = [];
                $exctaxes_array = [];
                foreach ($ledger->inctaxes as $inctax) {
                    if ($inctax->per != null) {
                        $tax = $total * ($inctax->per / 100);
                        $total_tax = $total_tax + $tax;
                        $inctax_array = [
                            'id' => $inctax->id,
                            'name' => $inctax->name,
                            'name_loc' => $inctax->name_loc,
                            'value' => $tax,
                            'type' => 'percentage',
                        ];

                        array_push($inctaxes_array, $inctax_array);
                    } else {
                        $total_tax = $total_tax + $inctax->amount;
                        $inctax_array = [
                            'id' => $inctax->id,
                            'name' => $inctax->name,
                            'name_loc' => $inctax->name_loc,
                            'value' => $inctax->amount,
                            'type' => 'amount',
                        ];
                        array_push($inctaxes_array, $inctax_array);
                    }
                }
                $total = $total + $total_tax;
                $whole_tax = $total_tax;
                $total_with_taxes->put('total_inc', $total_tax);
                $total_with_taxes->put('inctaxes', $inctaxes_array);
                $total_tax = 0;
                foreach ($ledger->taxes as $exctax) {
                    if ($exctax->per != null) {
                        $tax = $total * ($exctax->per / 100);
                        $total_tax = $total_tax + $tax;
                        $exctax_array = [
                            'id' => $exctax->id,
                            'name' => $exctax->name,
                            'name_loc' => $exctax->name_loc,
                            'value' => $tax,
                            'type' => 'percentage',
                        ];

                        array_push($exctaxes_array, $exctax_array);
                    } else {
                        $total_tax = $total_tax + $exctax->amount;
                        $exctax_array = [
                            'id' => $exctax->id,
                            'name' => $exctax->name,
                            'name_loc' => $exctax->name_loc,
                            'value' => $exctax->amount,
                            'type' => 'amount',
                        ];
                        array_push($exctaxes_array, $exctax_array);
                    }
                }
                $total = $total + $total_tax;
                $whole_tax = $whole_tax + $total_tax;

                $total_with_taxes->put('total_tax_amount', $whole_tax);
                $total_with_taxes->put('total', $total);
                $total_with_taxes->put('total_exc', $total_tax);
                $total_with_taxes->put('exctaxes', $exctaxes_array);
            } else {
                $total_with_taxes = collect(['total' => $amount, 'total_with_tax' => 0, 'inctaxes' => [], 'exctaxes' => []]);
                $total = $amount;
                $total_tax = 0;
                $whole_tax = 0;
                $total_per_inc = 0;
                $total_per_exc = 0;
                $total_without_inc = 0;
                $total_without_exc = 0;
                $inctaxes_array = [];
                $exctaxes_array = [];
                foreach ($ledger->taxes as $exctax) {
                    if ($exctax->per != null) {
                        $total_per_exc = $total_per_exc + $exctax->per;
                    } else {
                    }
                }
                foreach ($ledger->inctaxes as $inctax) {
                    if ($inctax->per != null) {
                        $total_per_inc = $total_per_inc + $inctax->per;
                    } else {
                    }
                }

                $total_without_exc = $amount / (($total_per_exc / 100) + 1);
                $total_without_inc = $total_without_exc / (($total_per_inc / 100) + 1);

                foreach ($ledger->inctaxes as $inctax) {
                    if ($inctax->per != null) {
                        $tax = $total_without_inc * ($inctax->per / 100);
                        $total_tax = $total_tax + $tax;
                        $inctax_array = [
                            'id' => $inctax->id,
                            'name' => $inctax->name,
                            'name_loc' => $inctax->name_loc,
                            'value' => $tax,
                            'type' => 'percentage',
                        ];
                        array_push($inctaxes_array, $inctax_array);
                    } else {
                    }
                }

                foreach ($ledger->taxes as $exctax) {
                    if ($exctax->per != null) {
                        $tax = $total_without_exc * ($exctax->per / 100);
                        $total_tax = $total_tax + $tax;
                        $exctax_array = [
                            'id' => $exctax->id,
                            'name' => $exctax->name,
                            'name_loc' => $exctax->name_loc,
                            'value' => $tax,
                            'type' => 'percentage',
                        ];
                        array_push($exctaxes_array, $exctax_array);
                    } else {
                    }
                }

                $total_with_taxes->put('total_with_tax', $total_without_inc);
                $total_with_taxes->put('inctaxes', $inctaxes_array);
                $total_with_taxes->put('exctaxes', $exctaxes_array);
            }
            return response()->json($total_with_taxes);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }

    private static function tax_exc($ledger, $amount)
    {
    }
    private function tax_inc($ledger, $amount)
    {
    }
}
