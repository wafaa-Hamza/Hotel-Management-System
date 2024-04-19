<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AccountCustomize;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AccountCustomizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
//        $this->authorize('view-account customize');

        return response()->json(AccountCustomize::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
//        $this->authorize('create-account customize');

        try {
            $request->validate([
                'type'           => 'string|in:P&L,BLS,CUS',
            ]);
            $account_customize = [];
            $AccountCRIds = $request->CR;
            $AccountDRIds = $request->DR;

            foreach ($AccountCRIds as $CRaccount) {

                $account_customize = AccountCustomize::create([
                    'account_id'   => $CRaccount,
                    'type'         => $request->type,
                    'SaveAs'       => 'CR',

                ]);
            }
            foreach ($AccountDRIds as $DRaccount) {

                $account_customize = AccountCustomize::create([
                    'account_id'   => $DRaccount,
                    'type'         => $request->type,
                    'SaveAs'       => 'DR',

                ]);
            }
            return response()->json([

                'message'  => 'Account Customize Created Successfully',

            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 400);
        }
    }


    public function Accounts_Profit_and_Loss(Request $request)
    {
//        $this->authorize('view-account customize');

        $cr = [];
        $dr = [];
        $start = Carbon::parse($request->startDate);
        $end = Carbon::parse($request->endDate);
        $formattedDateStart = $start->format('Y-m-d H:i:s');
        $formattedDateEnd = $end->format('Y-m-d H:i:s');

        $accountCR = AccountCustomize::with('ChartOfAccount')->where('type', 'P&L')->where('saveAs', "CR")->get();
        $accountDR = AccountCustomize::with('ChartOfAccount')->where('type', 'P&L')->where('saveAs', "DR")->get();

        foreach ($accountCR as $credit) {

            array_push($cr, [
                'code'       => optional($credit->ChartOfAccount)->account_code,
                'name'       => optional($credit->ChartOfAccount)->account_name,
                'name_loc'   => optional($credit->ChartOfAccount)->account_name_loc,
                'balance'    => optional($credit->ChartOfAccount)->balance($formattedDateStart, $formattedDateEnd)
            ]);
        }

        foreach ($accountDR as $debit) {

            array_push($dr, [
                'code'       => optional($debit->ChartOfAccount)->account_code,
                'name'       => optional($debit->ChartOfAccount)->account_name,
                'name_loc'   => optional($debit->ChartOfAccount)->account_name_loc,
                'balance'    => optional($debit->ChartOfAccount)->balance($formattedDateStart, $formattedDateEnd)
            ]);
        }
        $data = [
            'Credit'  => $cr,
            'Debit'   => $dr,

        ];

        return $data;
    }


    public function Balance_Sheet(Request $request)
    {
//        $this->authorize('view-account customize');

        $crSheet = [];
        $drSheet = [];
        $start = Carbon::parse($request->startDate);
        $end = Carbon::parse($request->endDate);
        $formattedDateStart = $start->format('Y-m-d H:i:s');
        $formattedDateEnd = $end->format('Y-m-d H:i:s');

        $accountCRsheet = AccountCustomize::with('ChartOfAccount')->where('type', 'BLS')->where('saveAs', "CR")->get();
        $accountDRsheet = AccountCustomize::with('ChartOfAccount')->where('type', 'BLS')->where('saveAs', "DR")->get();

        foreach ($accountCRsheet as $creditSheet) {
            //return $creditSheet->ChartOfAccount->account_code;
            array_push($crSheet, [
                'code'       => optional($creditSheet->ChartOfAccount)->account_code,
                'name'       => optional($creditSheet->ChartOfAccount)->account_name,
                'name_loc'   => optional($creditSheet->ChartOfAccount)->account_name_loc,
                'balance'    => optional($creditSheet->ChartOfAccount)->balanceSheet($formattedDateStart, $formattedDateEnd)
            ]);
        }

        foreach ($accountDRsheet as $debitSheet) {
            //  return $debitSheet;
            array_push($drSheet, [
                'code'       => optional($debitSheet->ChartOfAccount)->account_code,
                'name'       => optional($debitSheet->ChartOfAccount)->account_name,
                'name_loc'   => optional($debitSheet->ChartOfAccount)->account_name_loc,
                'balance'    => optional($debitSheet->ChartOfAccount)->balanceSheet($formattedDateStart, $formattedDateEnd)
            ]);
        }
        $data = [
            'Credit'  => $crSheet,
            'Debit'   => $drSheet,
        ];

        return $data;
    }

    public function Get_CR_DR(Request $request)
    {
//        $this->authorize('view-account customize');

        $CR = [];
        $DR = [];
        $typeCollection = $request->type;

        $get_cr = AccountCustomize::where('type', $typeCollection)->where('saveAs', 'CR')->with('ChartOfAccount')->get();
        $get_dr = AccountCustomize::where('type', $typeCollection)->where('saveAs', 'DR')->with('ChartOfAccount')->get();

        foreach ($get_cr as $CrCollection) {

            // return $CrCollection;
            array_push($CR, $CrCollection->account_id);
        }
        foreach ($get_dr as $DrCollection) {

            array_push($DR, $DrCollection->account_id);
        }
        $data = [
            'CR'  =>  $CR,
            'DR'   => $DR,

        ];

        return $data;
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
