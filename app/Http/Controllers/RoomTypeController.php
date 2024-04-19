<?php

namespace App\Http\Controllers;

use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RoomTypeController extends Controller
{

    public function index()
    {
        //        $this->authorize('view-roomtype');

        return response()->json(RoomType::whereNot('rm_type_name', 'payMaster')->get());
    }
    public function roomTypePagiantion()
    {
        //        $this->authorize('view-roomtype');

        return response()->json(RoomType::whereNot('rm_type_name', 'payMaster')->paginate(request()->segment(count(request()->segments()))));
    }
    public function Max_sort_order()
    {
        //        $this->authorize('view-roomtype');

        $get_sort = RoomType::max('sort_order');
        return $get_sort;
    }

    public function store(Request $request)
    {

        try {
            $request->validate([
                'rm_type_code' => 'required|string|max:20',
                'rm_type_name' => 'required|string|max:255',
                'rm_type_name_loc' => 'required|string|max:255',
                'def_pax' => 'required|integer',
                'def_price' => 'required|numeric',
                'no_of_rooms' => 'required|integer',
                'monthly_rate' => 'nullable|numeric',
                'yearly_rate' => 'nullable|numeric',
                'rm_type_rentable' => 'required|boolean',
                'sort_order' => 'integer',
                'scth_type_code' => 'required|integer',
                'def_rate_code' => 'required|string',
            ]);
        } catch (ValidationException $e) {

            return response()->json(["message" => $e->errors()->first()], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        //        $this->authorize('create-roomtype');


        $roomtype = RoomType::create([
            'rm_type_code' => $request->rm_type_code,
            'rm_type_name' => $request->rm_type_name,
            'rm_type_name_loc' => $request->rm_type_name_loc,
            'def_pax' => $request->def_pax,
            'def_price' => $request->def_price,
            'no_of_rooms' => $request->no_of_rooms,
            'monthly_rate' => $request->monthly_rate,
            'yearly_rate' => $request->yearly_rate,
            'rm_type_rentable' => $request->rm_type_rentable,
            'sort_order' => $this->Max_sort_order() + 1,
            'scth_type_code' => $request->scth_type_code,
            'def_rate_code' => $request->def_rate_code,
        ]);
        return response()->json($roomtype);
    }

    public function show(RoomType $roomType)
    {
        //        $this->authorize('');

        return response()->json($roomType);
    }

    public function update(Request $request, RoomType $roomType)
    {
        try {
            $request->validate([
                'rm_type_code' => 'required|string|max:20',
                'rm_type_name' => 'required|string|max:255',
                'rm_type_name_loc' => 'required|string|max:255',
                'def_pax' => 'required|integer',
                'def_price' => 'required|numeric',
                'no_of_rooms' => 'required|integer',
                'monthly_rate' => 'nullable|numeric',
                'yearly_rate' => 'nullable|numeric',
                'rm_type_rentable' => 'required|boolean',
                'sort_order' => 'integer',
                'scth_type_code' => 'required|integer',
                'def_rate_code' => 'required|string',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json($e->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        //        $this->authorize('edit-roomtype');

        $status = $roomType->update([
            'rm_type_code' => $request->rm_type_code,
            'rm_type_name' => $request->rm_type_name,
            'rm_type_name_loc' => $request->rm_type_name_loc,
            'def_pax' => $request->def_pax,
            'def_price' => $request->def_price,
            'no_of_rooms' => $request->no_of_rooms,
            'monthly_rate' => $request->monthly_rate,
            'yearly_rate' => $request->yearly_rate,
            'rm_type_rentable' => $request->rm_type_rentable,
            'sort_order' => $request->sort_order,
            'scth_type_code' => $request->scth_type_code,
            'def_rate_code' => $request->def_rate_code,
        ]);
        return response()->json($status);
    }

    public function destroy(RoomType $roomType)
    {
        //        $this->authorize('delete-roomtype');

        $roomTypeForDelete = $roomType->whereHas('rooms')->where('id', $roomType->id)->count();
        // dd($roomTypeForDelete);
        if ($roomTypeForDelete == 0) {
            $roomType->delete();
            return response()->json("deleted");
        } else {
            return response()->json("this room type have been used");
        }
    }
}
