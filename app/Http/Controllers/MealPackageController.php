<?php

namespace App\Http\Controllers;

use App\Models\MealPackage;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MealPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $this->authorize('view-mealpackage');

        $mealPackages = MealPackage::with('meal')->get();

        return response()->json($mealPackages);
    }
    public function mealPackagePagination()
    {
//        $this->authorize('view-mealpackage');

        $mealPackages = MealPackage::with('meal')->paginate(request()->segment(count(request()->segments())));

        return response()->json($mealPackages);
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
                'package_code' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'name_loc' => 'required|string|max:255',
                'meal_id' => 'nullable|exists:meals,id|array',

            ]);
//            $this->authorize('create-mealpackage');

            $mealPackage = MealPackage::create([
                'package_code' => $request->input('package_code'),
                'name' => $request->input('name'),
                'name_loc' => $request->input('name_loc'),
            ]);
            $mealPackage->meal()->sync($request->meal_id);
            return response()->json($mealPackage, 201);
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
     * @param  \App\Models\MealPackage  $mealPackage
     * @return \Illuminate\Http\Response
     */
    public function show(MealPackage $mealPackage)
    {
//        $this->authorize('view-mealpackage');

        return response()->json($mealPackage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MealPackage  $mealPackage
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MealPackage $mealPackage)
    {
        try {
            $request->validate([
                'package_code' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'name_loc' => 'required|string|max:255',
                'meal_id' => 'nullable|exists:meals,id|array',

            ]);
//            $this->authorize('edit-mealpackage');

            $mealPackage->meal()->sync($request->meal_id);
            $mealPackage->update([
                'package_code' => $request->input('package_code'),
                'name' => $request->input('name'),
                'name_loc' => $request->input('name_loc'),

            ]);
            return response()->json($mealPackage, 201);
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
     * @param  \App\Models\MealPackage  $mealPackage
     * @return \Illuminate\Http\Response
     */
    public function destroy(MealPackage $mealPackage)
    {
//        $this->authorize('delete-mealpackage');

        $mealPackage->delete();
        return response()->json(['message' => 'Meal package deleted']);
    }
}
