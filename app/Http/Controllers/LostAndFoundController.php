<?php

namespace App\Http\Controllers;

use App\Models\LostAndFound;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class LostAndFoundController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-lostandfound');

        $lostsAndFounds = LostAndFound::with('delivery_by')->get();
        return response()->json(['data' => $lostsAndFounds]);
    }
    public function lostAndFoundPagination()
    {
        $this->authorize('view-lostandfound');

        $lostsAndFounds = LostAndFound::with('delivery_by')->paginate(request()->segment(count(request()->segments())));
        return response()->json(['data' => $lostsAndFounds]);
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
            $validatedData = $request->validate([
                'guest_id' => 'nullable|exists:guests,id',
                'description' => 'required|max:10000',
                'found_date' => 'required|date',
                'found_by' => 'required|min:3|max:50',
                'delivery_status' => ['required', Rule::in(['pending'])],
            ]);
            $this->authorize('create-lostandfound');


            $lostAndFound = LostAndFound::create($validatedData);
            return response()->json(['data' => $lostAndFound], 201);
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
     * @param  \App\Models\LostAndFound  $lostAndFound
     * @return \Illuminate\Http\Response
     */
    public function show(LostAndFound $lostAndFound)
    {
        $this->authorize('view-lostandfound');

        $lostAndFound = LostAndFound::with('delivery_by')->where('id',$lostAndFound->id)->get();
        return response()->json(['data' => $lostAndFound]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\LostAndFound  $lostAndFound
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LostAndFound $lostAndFound)
    {
        $validatedData = $request->validate([
            'delivery_status' => ['required', Rule::in(['delivered', 'closed'])],
            'delivery_date' => 'nullable|date',
            'delivery_by' => 'required|exists:users,id',
            'delivery_to' => 'nullable|max:50',
        ]);
        $this->authorize('edit-lostandfound');

        if ($lostAndFound->delivery_status == 'pending') {
            $lostAndFound->update($validatedData);
        } else {
            return response()->json(['error' => 'delivery status is already ' . $lostAndFound->delivery_status]);
        }
        return response()->json(['data' => $lostAndFound]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LostAndFound  $lostAndFound
     * @return \Illuminate\Http\Response
     */
    public function destroy(LostAndFound $lostAndFound)
    {
        $this->authorize('delete-lostandfound');

        $lostAndFound->delete();
        return response()->json(['message' => 'lost and found deleted']);
    }
}
