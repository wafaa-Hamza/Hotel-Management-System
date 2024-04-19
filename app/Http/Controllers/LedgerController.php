<?php

namespace App\Http\Controllers;

use App\Models\Ledger;
use App\Models\LedgerCat;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LedgerController extends Controller
{
    public function ledger_cats()
    {
        //        $this->authorize('delete-ledger');
        return response()->json(LedgerCat::with(['ledgers.taxes', 'ledgers.inctaxes'])->get());
    }
    public function index()
    {
        //        $this->authorize('view-ledger');
        return response()->json(Ledger::with('inctaxes', 'taxes')->get());
    }
    public function ledgerPagination()
    {
        //        $this->authorize('view-ledger');

        return response()->json(Ledger::paginate(request()->segment(count(request()->segments()))));
    }
    public function store(Request $request)
    {
        try {
          $validated =  $request->validate([
                'code' => 'required|integer',
                'name' => 'required|string|max:255',
                'name_loc' => 'required|string|max:255',
                'type' => 'required|string',
                'ledger_cat_id' => 'required|exists:ledger_cats,id',
                'inctaxes' => 'array|nullable',
                'taxes' => 'array|nullable',
                'gl_account' => 'nullable',
            ]);
            //            $this->authorize('create-ledger');


            $ledger = Ledger::create($validated);

            $ledger->inctaxes()->syncWithPivotValues($request->inctaxes, ['inc' => true]);

            $ledger->taxes()->syncWithPivotValues($request->taxes, ['inc' => false]);

            return response()->json([
                'message' => 'Ledger created successfully',
                'data' => $ledger,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }
    public function show(Ledger $ledger)
    {
        //        $this->authorize('view-ledger');

        $ledgerdetails = Ledger::where('id', $ledger->id)->with('inctaxes', 'taxes')->get();
        return response()->json($ledgerdetails);
    }
    public function update(Request $request, Ledger $ledger)
    {
        try {
         $validated =   $request->validate([
                'code' => 'required|integer',
                'name' => 'required|string|max:255',
                'name_loc' => 'required|string|max:255',
                'type' => 'required|string',
                'ledger_cat_id' => 'required|exists:ledger_cats,id',
                'inctaxes' => 'array|nullable',
                'taxes' => 'array|nullable',
                'gl_account' => 'nullable',
            ]);
            //            $this->authorize('edit-ledger');


            $ledger->update($validated);

            $ledger->inctaxes()->syncWithPivotValues($request->inctaxes, ['inc' => true]);

            $ledger->taxes()->syncWithPivotValues($request->taxes, ['inc' => false]);

            return response()->json([
                'message' => 'Ledger updated successfully',
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }
    public function destroy(Ledger $ledger)
    {
        //        $this->authorize('delete-ledger');

        $ledger->delete();
        return response()->json([
            'message' => 'Ledger deleted successfully',
        ], 201);
    }
}
