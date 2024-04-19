<?php

namespace App\Http\Controllers;

use App\Models\SourceBusiness;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SourceBusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $this->authorize('view-sourcebusiness');


        return response()->json(SourceBusiness ::all());
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
                'name'     => 'required|unique:businesses,name|max:254',
                'name_loc' => 'required|unique:businesses,name_loc|max:254',
            ]);
//            $this->authorize('create-sourcebusiness');

            $sourceBusiness= SourceBusiness::create($request->all());
            // dd($sourceBusiness);

            return response()->json([
                'message' => 'Source Of  Business  Created Successfully',
                'data'    =>  $sourceBusiness ,
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
     * @param  \App\Models\SourceBusiness  $sourceBusiness
     * @return \Illuminate\Http\Response
     */
    public function show(SourceBusiness $sourceBusiness)
    {
//        $this->authorize('view-sourcebusiness');

       return response()->json($sourceBusiness->id::all());
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SourceBusiness  $sourceBusiness
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SourceBusiness $sourceBusiness,$id)
    {
        $request->validate([
            'name'      => 'nullable|max:254|unique:businesses,name,'.$id,
            'name_loc'  => 'nullable|max:254|unique:businesses,name_loc,'.$id,

       ]);
     
       $this->authorize('edit-source of business');

        $sourceBusinessDAta = $sourceBusiness->where('id',$id)->first();

       $sourceBusiness= SourceBusiness::where('id',$id)->update([
           'name'     => (!empty($request->name))?$request->name:$sourceBusinessDAta->name,
           'name_loc' => (!empty($request->name_loc))?$request->name_loc:$sourceBusinessDAta->name_loc,
       ]);
       $sourceBusiness  = SourceBusiness::where('id', $id)->get();

       return response(['data' =>  'Source Of  Business  Update Successfully' ,
    ], 200);
   }
    

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SourceBusiness  $sourceBusiness
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        $this->authorize('delete-sourcebusiness');

        $sourceBusines=SourceBusiness::where('id',$id)->delete();
        return response()->json([
            'status'     => true,
            'message'    => 'Source Of  Business Deleted Successfully',
        ],201);
    }
    
}
