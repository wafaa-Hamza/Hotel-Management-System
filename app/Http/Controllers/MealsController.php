<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MealsController extends Controller
{
    public function index()
    {
//        $this->authorize('view-meal');

        $meals = Meal::with('meal_package','ledger')->get();

        return response()->json($meals);
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                'meal_code' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'name_loc' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'ledger_id' => 'required|exists:ledgers,id',
                'meal_type' => 'required|in:BF,LN,DI,IF,SU,OT',
              //  'meal_package_id' => 'nullable|exists:meal_packages,id|array',

            ]);
//            $this->authorize('create-meal');

            $meal = Meal::create([
                'meal_code' => $request->input('meal_code'),
                'name' => $request->input('name'),
                'name_loc' => $request->input('name_loc'),
                'price' => $request->input('price'),
                'ledger_id' => $request->input('ledger_id'),
                'meal_type' => $request->input('meal_type'),
            ]);
          //  $meal->meal_package()->sync($request->input('meal_package_id'));
            return response()->json($meal, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }

    public function show(Meal $meal)
    {
//        $this->authorize('view-meal');

        return response()->json($meal);
    }

    public function update(Request $request, Meal $meal)
    {
         try {
            $request->validate([

                'meal_code' => 'required|string|max:255',
                'name' => 'required|string|max:255',
                'name_loc' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'ledger_id' => 'required|exists:ledgers,id',
               // 'meal_package_id' => 'nullable|exists:meal_packages,id|array',
                'meal_type' => 'required|in:BF,LN,DI,IF,SU,OT',
            ]);
//            $this->authorize('edit-meal');
$meal->update([
    'meal_code' => !empty($request->meal_code) ? $request->meal_code : $meal->meal_code,
    'name' => !empty($request->name) ? $request->name : $meal->name,
    'name_loc' => !empty($request->name_loc) ? $request->name_loc : $meal->name_loc,
    'price' => !empty($request->price) ? $request->price : $meal->price,
    'ledger_id' => !empty($request->ledger_id) ? $request->ledger_id : $meal->ledger_id,
    'meal_type' => !empty($request->meal_type) ? $request->meal_type : $meal->meal_type,
]);
//$mealUpdatedData = $meal->first();

           // $meal->meal_package()->sync($request->input('meal_package_id'));

            return response()->json($meal);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }

    public function destroy(Meal $meal)
    {
//        $this->authorize('delete-meal');

        $meal->delete();

        return response()->json(['message' => 'Meal deleted']);
    }
}
