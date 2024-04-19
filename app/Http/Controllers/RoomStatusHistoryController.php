<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\RoomStatusHistory;
use Illuminate\Validation\ValidationException;

class RoomStatusHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $room_status_history = RoomStatusHistory::get();
        return $room_status_history;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function InsertStatusChange($room_id, $description, $fo_status_from, $fo_status_to, $hk_status_from, $hk_status_to)
    {
        $hotelDate = Setting::select('hotel_date')->first()->hotel_date;;
        //  dd($hotelDate);
        try {

            $insert_status = new RoomStatusHistory();
            // dd($insert_status  ->hk_status_to );
            $insert_status->room_id                 = $room_id;
            $insert_status->description             = $description;
            $insert_status->fo_status_from          = $fo_status_from;
            $insert_status->fo_status_to            = $fo_status_to;
            $insert_status->hk_status_from          = $hk_status_from;
            $insert_status->hk_status_to            = $hk_status_to;
            $insert_status->user_id                 = (!empty(auth()->user()->id)) ? auth()->user()->id : 1;
            $insert_status->hotel_date              = $hotelDate;
            $insert_status->save();

            return response()->json([
                'message'  => 'Room Status History Created Successfully',
                'data'     =>   $insert_status,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 400);
        }
    }

    public function GetByRoomID(Request $request)
    {

        $get_roomID = RoomStatusHistory::whereBetween('hotel_date', [$request->startDate, $request->endDate])
            ->where('room_id', $request->room_id)
            ->with('rooms')->get();
        return $get_roomID;
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
