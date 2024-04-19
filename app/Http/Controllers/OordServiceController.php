<?php

namespace App\Http\Controllers;

use App\helpers;
use App\Models\Room;
use App\Models\Setting;
use App\Models\OordService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\RoomStatusHistoryController;
use App\Repositoryinterface\RepositoryTaskDetailsinterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestInhouseInterface;

class OordServiceController extends Controller
{
    use helpers;
    private $guestInhouseInterface;
    private $taskDetailInterface;
    public function __construct(RepositoryGuestInhouseInterface $guestInhouseInterface,RepositoryTaskDetailsinterface $taskDetailInterface)
    {
        $this->guestInhouseInterface = $guestInhouseInterface;
        $this->taskDetailInterface = $taskDetailInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-oordservices');

        $OordService = OordService::with('room', 'created_by', 'return_by')->get();
        return response()->json(['data' => $OordService]);

        //        return response()->json(OordService::all());
    }
    public function oOrdServicesPagination()
    {
        $this->authorize('view-oordservices');

        return response()->json(OordService::paginate(request()->segment(count(request()->segments()))));
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
                'room_id' => 'required|array|max:254',
                'oord_type' => 'required|in:OO,OS',
                'start_date' => 'date',
                'end_date' => 'date',
                'notes' => 'nullable|string',
                'created_by' => 'integer',
                'is_return' => 'boolean',
                'return_by' => 'nullable',
                'return_date' => 'nullable',

                // $this->authorize('create-oordservices');

            ]);

            $rooms = $request->room_id;
            foreach ($rooms as $room) {

                $oordService = new OordService();

                $oordService->room_id = $room;
                $oordService->oord_type = strtoupper($request->oord_type);

                $hotelDate = Setting::select('hotel_date')->first()->hotel_date;

                $oordService->start_date = $request->start_date;
                $oordService->end_date = $request->end_date;
                $oordService->notes = $request->notes;
                $oordService->created_by = (!empty(auth()->user()->id)) ? auth()->user()->id : 1;

                $oordService->save();

                $roomStatusController = new RoomStatusHistoryController();
                if ($request->start_date == $hotelDate) {

                    $update_rooms = Room::where('id', $room)->first();

                    $ss = $this->guestInhouseInterface->changeRoomStatus($room, strtoupper($request->oord_type), 'change By oord-service', $update_rooms->hk_stauts);

                    // $roomStatusController->InsertStatusChange(
                    //     $room,
                    //     'change By oord-service',
                    //     $update_rooms->fo_status,
                    //     $request->oord_type,
                    //     $update_rooms->hk_stauts,
                    //     $update_rooms->hk_stauts
                    // );

                    // $update_rooms->update(['fo_status' => $request->oord_type]);

                };
            }

            return response()->json([
                'message' => 'Order-Service Created Successfully',
                'data' => $oordService,
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $oordService = OordService::find($id);

        $this->authorize('view-oordservices');

        $oordService = OordService::find($id);
        return response()->json($oordService);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'end_date' => 'required|date',
            'note' => 'required|string',
        ]);
        $this->authorize('edit-oordservices');

        $oordService = OordService::where('id', $id)->update([

            'end_date' => $request->end_date,
            'notes' => $request->note,

        ]);
        if($request->end_date != $this->settings()->first()->hotel_date)
        {$this->taskDetailInterface->publicRefreshTask('OOandOSREturn',1,'oo&os_Return');}
        return response([

            'message' => 'Order-Service Updated Successfully',
            'data' => $oordService,
        ], 200);
    }

    public function returnOORD(Request $request, $id)
    {
        $v = time();
        $v_as = date("h:i:s", $v);
        $request->validate([
            'is_return' => 'required|boolean',
            'return_date' => 'required|date',
        ]);
        $this->authorize('edit-oordservices');

        $oordService = OordService::where('id', $id)->update([

            'is_return' => 1,
            'return_by' => auth()->user()->id,
            'return_date' => $request->return_date.' '.$v_as,

        ]);
        $oordService = OordService::where('id', $id)->first();

        if ($oordService->is_return = true) {
            $this->guestInhouseInterface->changeRoomStatus($oordService->room_id, 'VA', 'change By Retrun oord-service', 'DI');
            $this->taskDetailInterface->publicRefreshTask('OOandOSREturn',1,'oo&os_Return');
        }
        return response([

            'message' => 'Order-Service Updated Successfully',
            'data' => $oordService,
        ], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $oordService = OordService::where('id', $id)->delete();

        $this->authorize('delete-oordservices');

        $oordService = OordService::where('id', $id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Order-Service  Deleted Successfully',
        ], 201);
    }
}
