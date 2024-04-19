<?php

namespace App\Http\Controllers;

use App\Models\Market;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class MarketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $this->authorize('view-market');


        return response()->json(Market::all());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            $request->validate([
                'name'     => 'required|unique:markets|max:254',
                'name_loc' => 'required|unique:markets|max:254',
            ]);
//            $this->authorize('create-market');


            $market = Market::create($request->all());

            return response()->json([
                'message' => 'Segmentation Created Successfully',
                'data'    =>  $market ,
            ], 201);

        }catch (ValidationException $e) {
            return response()->json([
                'message'   => 'Validation Error',
                'errors'    => $e->errors(),
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Market  $market
     * @return \Illuminate\Http\Response
     */
    public function show(Market $market)
    {
//        $this->authorize('view-market');

        return response()->json($market->id::all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *  @param  \App\Models\Market  $market
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Market $market,$id)
    {
//dd($id);
        $request->validate([
             'name'     => 'nullable|max:254|unique:markets,name,'.$id,
             'name_loc' => 'nullable|max:254|unique:markets,name_loc,'.$id,

        ]);
//        $this->authorize('edit-market');


      
$marketDAta = $market->where('id',$id)->first();
        $market = Market::where('id',$id)->update([
            'name'     => (!empty($request->name))?$request->name:$marketDAta->name,
            'name_loc' => (!empty($request->name_loc))?$request->name_loc:$marketDAta->name_loc,
        ]);
        $market  = Market::where('id', $id)->get();

        return response(['data' =>  'Update successfully' ], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     
    
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        $this->authorize('delete-market');


        $market=Market::where('id',$id)->delete();
        return response()->json([
            'status'     => true,
            'message'    => 'Segmentation Deleted Successfully',
        ],201);
    }
}


        // $permission = Permission::where('id',$request->id)->update([
        //     'name' => $request->name,
        // ]);
        // $permission = Permission::where('id', $request->id)->get();

        // return $this->apiResponse(['data' => $permission], 200);
