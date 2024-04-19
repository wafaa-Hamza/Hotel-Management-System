<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\v1\Profiles\GuestProfile\ReservationController;
use App\Models\ReservationStatus;
use Illuminate\Http\Request;

class ReservationStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-reservissionstatus');

        $reservationStatus = ReservationStatus::all();
        return response()->json(['data' => $reservationStatus]);
    }
    public function rservationStatusPagination()
    {
        $this->authorize('view-reservissionstatus');

        $reservationStatus = ReservationStatus::paginate(request()->segment(count(request()->segments())));
        return response()->json(['data' => $reservationStatus]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ReservationStatus  $reservationStatus
     * @return \Illuminate\Http\Response
     */
    public function show(ReservationStatus $reservationStatus)
    {
        $this->authorize('view-reservissionstatus');

        return response()->json(['data' => $reservationStatus]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ReservationStatus  $reservationStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReservationStatus $reservationStatus)
    {
        
        $validatedData = $request->validate([

            'color' => 'required|string|max:255',
        ]);
        $this->authorize('edit-reservissionstatus');


        $reservationStatus->color = $validatedData['color'];
        $reservationStatus->save();
        return response()->json(['data' => $reservationStatus]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ReservationStatus  $reservationStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReservationStatus $reservationStatus)
    {
        //
    }
}