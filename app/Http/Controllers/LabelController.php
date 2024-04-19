<?php

namespace App\Http\Controllers;

use App\Http\Requests\LabelRequest;
use App\Models\Label;

class LabelController extends Controller
{
    public function index()
    {
        $this->authorize('view-label');

        $labels = Label::all();

        return response()->json($labels);
    }
    public function labelPagination()
    {
        $this->authorize('view-label');

        $labels = Label::paginate(request()->segment(count(request()->segments())));

        return response()->json($labels);
    }

    public function store(LabelRequest $request)
    {
        $this->authorize('create-label');

        $label = Label::create($request->validated());

        return response()->json($label);
    }

    public function show(Label $label)
    {
        $this->authorize('view-label');

        return response()->json($label);
    }
    public function update(LabelRequest $request, Label $label)
    {
        $this->authorize('edit-label');

        $label->update($request->validated());

        return response()->json($label);
    }

    public function destroy(Label $label)
    {
        $this->authorize('delete-label');

        $label->delete();

        return response()->json(['message' => 'Deleted Successfuly',
            'status' => true,
        ], 200);
    }
}