<?php

namespace App\Http\Controllers;

use App\Models\DetailsIntegration_Table;

use Illuminate\Http\Request;
use App\Models\MasterIntegrationTable;
use Illuminate\Validation\ValidationException;

class IntegrationTableController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $this->authorize('view-integration');

        return response()->json( MasterIntegrationTable::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $parameters=$request->parameters;
        try{
        $request->validate([
            'name'           => 'required|max:254',
            'name_loc'       => 'required|max:254',
            'cost'           =>'numeric',
            'start_date'     =>'required|after_or_equal:.date(Y-m-d H:i:s)',
            'end_date'       =>'required|after_or_equal:.date(Y-m-d H:i:s)',
            'is_active'       =>'nullable|',
        
        ]);
//        $this->authorize('create-integration');

       $Master_integration_table= MasterIntegrationTable::create([
        'name'          =>$request->name ,
        'name_loc'      =>$request->name_loc ,
        'cost'          =>$request->cost ,
        'start_date'    =>$request->start_date ,
        'end_date'      =>$request->end_date ,
        'is_active'      =>(!empty($request->is_active)) ? $request->is_active : 0 ,
       ]);
       foreach($parameters as $par){
       $Details_table= DetailsIntegration_Table::create([
        'master_id'       => $Master_integration_table->id ,
         'key'            =>$par['key'] ,
         'value'          =>encrypt($par['value'] ),

        ]);

       }
     
       return response()->json([
        'message'  => 'Integration Table Created Successfully',
        'data1'     =>  $Master_integration_table], 201);


        
       
    //    return response()->json([
    //     'message'  => 'Details Table Created Successfully',
    //     'data2'     => $Details_table], 201);

    
       }catch(ValidationException $e){
    
        return response()->json([
       'message' => 'Validation Error',
       'errors'  => $e->errors()],400);
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
//        $this->authorize('view-integration');

        $Master_integration_table=MasterIntegrationTable::find($id);
        return response()->json($Master_integration_table);

        $Details_table=DetailsIntegration_Table::find($id);
        return response()->json($Details_table);
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
//        $this->authorize('edit-integration');

        $validated = $request->validate([

            'name'           => 'required|max:254',
            'name_loc'       => 'required|max:254',
            'cost'           =>'numeric',
            'start_date'     =>'required|after_or_equal:.date(Y-m-d H:i:s)',
            'end_date'       =>'required|after_or_equal:.date(Y-m-d H:i:s)',
           
        ]);
        $parameters=$request->parameters;
       
        $Master_integration_table = MasterIntegrationTable::with('details_table')->where('id',$id);

        //dd($Master_integration_table);
       
        
       $Master_integration_table->update([
        'name'          =>$request->name ,
        'name_loc'      =>$request->name_loc ,
        'cost'          =>$request->cost ,
        'start_date'    =>$request->start_date ,
        'end_date'      =>$request->end_date ,
        'is_active'      =>$request->is_active,
        ]);
       
       $detail = DetailsIntegration_Table::where('master_id',$id)->forceDelete();
    
        foreach($parameters as $par){

            $Details_table= DetailsIntegration_Table ::create([
             'master_id'       => $id ,
              'key'            =>$par['key'] ,
              'value'          =>encrypt($par['value'] ),
             ]);
     
            }
        
        return response([

            'message'  => 'Master Integration Updated Successfully',
            'data' => $Master_integration_table->get()], 200);
       
       
       
    }



    public function Get_Value_BY_Key( $id , $key){
//        $this->authorize('view-integration');

         $Get_value_ByKey=DetailsIntegration_Table::
         where('master_id',$id)
         -> where('key', $key)->first();

      return response()->json([
            "detail"=>$Get_value_ByKey,
            "value"=>decrypt($Get_value_ByKey->value)
    ]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
//        $this->authorize('delete-integration');

        $Master_integration_table=MasterIntegrationTable ::where('id',$id)->delete();
        $Details_table=DetailsIntegration_Table ::where('id',$id)->delete();

        return response()->json([
            'status'     => true,
            'message'    => 'Integration Deleted Successfully',
        ],201);
    }
}
