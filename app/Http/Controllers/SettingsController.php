<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\GeneralSetting;
use Illuminate\Support\Carbon;
use Alkoumi\LaravelHijriDate\Hijri;
use Illuminate\Validation\ValidationException;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $this->authorize('view-serrings');


//        return response()->json(app('settings'));

        return response()->json(Setting::first());

    }
    public function show(Setting $Setting)
    {
//        $this->authorize('view-serrings');

        return response()->json($Setting);
        
    }
    
    public function store(Request $request)
    {
        /* $request->validate([
            'name' => 'required|string',
            'name_loc' => 'required|string',
            'type' => 'required|string',
            'cr_no' => 'required|string',
            'phone' => 'required|string',
            'mobile' => 'required|string',
            'email' => 'required|string',
            'city' => 'required|string',
            'address' => 'required|string',
            'vat_no' => 'required|string',
        ]);
        Setting::create($request->all()); */
    }

    public function update(Request $request, Setting $Setting)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'name_loc' => 'required|string',
                'type' => 'required|string',
                'cr_no' => 'required|string',
                'phone' => 'required|string',
                'mobile' => 'required|string',
                'email' => 'required|string',
                'city' => 'required|string',
                'address' => 'required|string',
                'vat_no' => 'required|string',
            ]);
//            $this->authorize('edit-serrings');


            $Setting->update($request->all());

            return response()->json($Setting, 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Error',
                'errors' => $e->errors(),
            ], 400);
        }
    }
    
}
