<?php

namespace App\Http\Controllers;

use App\Models\RoomStatus;
use Illuminate\Http\Request;

class RoomStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $this->authorize('view-roomstatus');

        $roomStatuses = RoomStatus::all();
        return response()->json(['data' => $roomStatuses]);
    }
    public function roomStatus()
    {
//        $this->authorize('view-roomstatus');

        $roomStatuses = RoomStatus::paginate(request()->segment(count(request()->segments())));
        return response()->json(['data' => $roomStatuses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\RoomStatus  $roomStatus
     * @return \Illuminate\Http\Response
     */
    public function show(RoomStatus $roomStatus)
    {
//        $this->authorize('view-roomstatus');

        return response()->json(['data' => $roomStatus]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\RoomStatus  $roomStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RoomStatus $roomStatus)
    {

        $validatedData = $request->validate([

            'color' => 'required|string|max:255',
        ]);
//        $this->authorize('edit-roomstatus');

        $roomStatus->color = $validatedData['color'];
        $roomStatus->save();
        return response()->json(['data' => $roomStatus]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\RoomStatus  $roomStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoomStatus $roomStatus)
    {
        //
    }
}
