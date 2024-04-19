<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Repositoryinterface\RepositoryTaskDetailsinterface;

class ChangeRoomStatusController extends Controller
{
    private $taskDetailInterface;
    public function __construct(RepositoryTaskDetailsinterface $taskDetailInterface)
    {
        $this->taskDetailInterface = $taskDetailInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {


        $this->authorize('edit-change room status');
        $roomStatusController = new RoomStatusHistoryController();

        try {
            $request->validate([
                'fo_status' => 'required',
                'hk_status' => 'required',
                'rooms' => 'required|array',

            ]);
            $updated_rooms = Room::wherein('id', $request->rooms)->get();

            foreach ($updated_rooms as $room) {


                $roomStatusController->InsertStatusChange($room->id, 'chang By roomStatus', $room->fo_status, $request->fo_status,  $room->hk_stauts, $request->hk_status);


                $room->update([
                    'fo_status' => $request->fo_status,
                    'hk_stauts' => $request->hk_status,
                ]);
                if($request->hk_status == 'CL')
                {
                    $this->taskDetailInterface->publicRefreshTask('cleanRooms',1,'clean_rooms');
                }
            }
            return response()->json(['message' => 'rooms successfully updated', 'data' => $room], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        //
    }
}
