<?php

namespace App\Http\Controllers;

use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class FloorController extends Controller
{
    public function index()
    {
        // $this->authorize('view-floor');

        return response()->json(Floor::with(['rooms', 'rooms.room_type'])->get());
    }
    public function floorPaginat()
    {
        // $this->authorize('view-floor');

        return response()->json(Floor::paginate(request()->segment(count(request()->segments()))));
    }

    public function Floor_by_rooms(Request $request)
    {

        $floor_with_rooms = Floor::withCount('rooms')->with('rooms.room_type')
            ->where('id', $request->floor_id)
            ->get();


        return response()->json(['floor_with_rooms' => $floor_with_rooms]);


    }
    public function get_floors_with_rooms()
    {
        $floor_with_rooms = Floor::withCount('rooms')->with('rooms.room_type')

            ->whereHas('rooms', function ($qu) {
                $qu->with('room_type');
            })

            ->get();


        return response()->json(['floor_with_rooms' => $floor_with_rooms]);
    }
    public function get_floor_with_rooms(Request $request)
    {
        $floor_with_rooms = Floor::withCount('rooms')->with('rooms.room_type')
            ->where('id', $request->floor_id)
            ->get();


        return response()->json(['floor_with_rooms' => $floor_with_rooms]);
    }
    public function get_floors_without_rooms()
    {
        $floor_without_rooms = Floor::whereDoesntHave('rooms', function ($qu) {
            $qu->with('room_type');
            $qu->where('rm_max_pax','>',0);
        })
            ->get();

        return response()->json(['floor_without_rooms' => $floor_without_rooms]);
    }
    public function Max_sort_order()
    {
        $get_sort = floor::max('sort_order');
        return $get_sort;
    }

    public function store(Request $request)
    {
        $this->authorize('create-floor');

        $request->validate([
            'floor_name'       => 'required',
            'floor_name_loc'   => 'required',
            'active'           => 'required',
            'no_of_rooms'      => 'required',
            'tower_id'      => 'nullable|exists:towers,id',
            //            'sort_order'       => 'string',

        ]);

        Floor::create([
            'floor_name'         => $request->floor_name,
            'floor_name_loc'     => $request->floor_name_loc,
            'active'             => $request->active,
            'no_of_rooms'        => $request->no_of_rooms,
            'tower_id'        => (!empty($request->tower_id)) ? $request->tower_id : 1,
            'sort_order'         => $this->Max_sort_order() + 1,
        ]);

        return response()->json([
            'status' => true,
            'message' => ' Floor Created Successfully',
        ], 200);
    }

    public function show(Floor $floor)
    {
        $this->authorize('view-floor');

        return response()->json(Floor::all());
    }

    public function update(Request $request, $id)
    {
        //$this->authorize('edit-floor');

        try {
            $request->validate([
                'floor_name'       => 'required',
                'floor_name_loc'   => 'required',
                'active'           => 'required',
                'no_of_rooms'      => 'required',

            ]);


            $floors = floor::where('id', $id)->update([
                'floor_name'         => $request->floor_name,
                'floor_name_loc'     => $request->floor_name_loc,
                'active'             => $request->active,
                'no_of_rooms'        => $request->no_of_rooms,

            ]);

            return response()->json([
                'message'  => 'Floor Updated Successfully',
                'data'     =>  $floors,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors'  => $e->errors(),
            ], 400);
        }
    }

    public function restoreTrashed($id)
    {
        $this->authorize('restore-deletedfloor');

        $floorTrashed = Floor::where('id', $id)->onlyTrashed()->get();
        if ($floorTrashed) {
            $floorRestored = Floor::withTrashed()->where('id', $id)->restore();

            return $floorTrashed;
        } else {
            return null;
        }
    }
    public function destroy($id)
    {
        //$this->authorize('delete-floor');

        $floor = floor::where('id', $id)->delete();
        return response()->json([
            'status'     => true,
            'message'    => 'Floor Deleted Successfully',
        ], 201);
    }
}
