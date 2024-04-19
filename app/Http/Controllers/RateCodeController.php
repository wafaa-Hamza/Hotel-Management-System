<?php

namespace App\Http\Controllers;

use App\Models\RateCode;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RateCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $this->authorize('view-ratecode');

        $ratecode = RateCode::with('meal_package', 'meal', 'user', 'ledger', 'roomTypes')->get();

        return response()->json($ratecode);
    }
    public function ratePagination()
    {
//        $this->authorize('view-ratecode');

        $ratecode = RateCode::with('meal_package', 'meal', 'user', 'ledger', 'room_ypes')->paginate(request()->segment(count(request()->segments())));

        return response()->json($ratecode);
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

            // Validate the request data
            $request->validate([
                'rate_code' => 'required|string|unique:rate_codes',
                'name' => 'required|string',
                'name_loc' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'active' => 'required|boolean',
                'meal_id' => 'nullable|prohibited_unless:meal_package_id,null|exists:meals,id',
                'meal_package_id' => 'nullable|prohibited_unless:meal_id,null|exists:meal_packages,id',
                'ledger_id' => 'required|exists:ledgers,id',
                'room_type_id' => 'array|required|exists:room_types,id',
            ]);
//            $this->authorize('create-ratecode');

            $rateCode = RateCode::create([
                'rate_code' => $request->input('rate_code'),
                'name' => $request->input('name'),
                'name_loc' => $request->input('name_loc'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'active' => $request->input('active'),
                'meal_id' => $request->input('meal_id'),
                'meal_package_id' => $request->input('meal_package_id'),
                'ledger_id' => $request->input('ledger_id'),
                'user_id' => auth()->user()->id,
            ]);

            $roomtypes = RoomType::whereIn('id', $request->room_type_id)->get();

            return response()->json([
                'ratecode_id' => $rateCode->id,
                'roomtypes' => $roomtypes,
                'message' => 'Rate code created successfully.'], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ],400);
        }
    }
    public function attach_room_type_to_rate_code($id, Request $request)
    {
//        $this->authorize('attach-roomtypetoratecode');

        $rateCode = RateCode::where('id', $id)->first();
        $array = $request->array;
        // Attach the room type to the rate code with pivot data
        foreach ($array as $pax) {
            $rateCode->roomTypes()->attach($pax['room_type_id'], [
                'pax1' => $pax['pax1'],
                'pax2' => $pax['pax2'],
                'pax3' => $pax['pax3'],
                'pax4' => $pax['pax4'],
                'pax5' => $pax['pax5'],
                'pax6' => $pax['pax6'],
                'extra_pax' => $pax['extra_pax'],
            ]);
        }
        return true;
    }

    public function update_room_type_to_rate_code($id, Request $request)
    {
//        $this->authorize('edit-roomtypetoratecode');

        $rateCode = RateCode::where('id', $id)->first();
        $array = $request->array;
        foreach ($array as $pax) {
            $rateCode->roomTypes()->detach($pax['room_type_id']);
        }

        // Attach the room type to the rate code with pivot data
        foreach ($array as $pax) {
            $rateCode->roomTypes()->attach($pax['room_type_id'], [
                'pax1' => $pax['pax1'],
                'pax2' => $pax['pax2'],
                'pax3' => $pax['pax3'],
                'pax4' => $pax['pax4'],
                'pax5' => $pax['pax5'],
                'pax6' => $pax['pax6'],
                'extra_pax' => $pax['extra_pax'],
            ]);
        }
        return true;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RateCode  $rateCode
     * @return \Illuminate\Http\Response
     */
    public function show(RateCode $rateCode)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RateCode  $rateCode
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RateCode $rateCode)
    {
        try {

            // Validate the request data
            $request->validate([
                'rate_code' => 'required|string|unique:rate_codes,rate_code,' . $rateCode->id . ',id',
                'name' => 'required|string',
                'name_loc' => 'required|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'active' => 'required|boolean',
                'meal_id' => 'nullable|prohibited_unless:meal_package_id,null|exists:meals,id',
                'meal_package_id' => 'nullable|prohibited_unless:meal_id,null|exists:meal_packages,id',
                'ledger_id' => 'required|exists:ledgers,id',
            ]);
//            $this->authorize('edit-ratecode');

            $rateCode->update([
                'rate_code' => $request->input('rate_code'),
                'name' => $request->input('name'),
                'name_loc' => $request->input('name_loc'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'active' => $request->input('active'),
                'meal_id' => $request->input('meal_id'),
                'meal_package_id' => $request->input('meal_package_id'),
                'ledger_id' => $request->input('ledger_id'),
                'user_id' => auth()->user()->id,
            ]);

            // Attach the room type to the rate code with pivot data

            return response()->json(['message' => 'Rate code updated successfully.'], 201);
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
     * @param  \App\Models\RateCode  $rateCode
     * @return \Illuminate\Http\Response
     */
    public function destroy(RateCode $rateCode)
    {
//        $this->authorize('delete-ratecode');

        $rateCode->delete();
        return response()->json(['message' => 'Rate code deleted successfully.'], 201);
    }
}
