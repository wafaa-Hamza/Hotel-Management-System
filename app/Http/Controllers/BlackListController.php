<?php

namespace App\Http\Controllers;

use App\Models\Black_List;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BlackListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('view-blacklist');

        return response()->json(Black_List::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->profile_id);
        //  $this->authorize('create-blacklist');
        if (($request->profile_id == null) || ($request->profile_id != null)) {

            try {

                $request->validate([
                    'profile_id'             => 'nullable',
                    'id_no'                  => 'required|integer',
                    'mobile_no'              => 'required|numeric',
                    'blacklist_reason'       => 'required|string',
                    'user_id'                => 'integer',
                    //'created_by'             =>'required|string'

                ]);
                // dd($request->validate);
                $Blacklist = Black_List::create([
                    'profile_id'              => $request->profile_id,
                    'id_no'                   => $request->id_no,
                    'mobile_no'               => $request->mobile_no,
                    'blacklist_reason'        => $request->blacklist_reason,

                    'user_id'                 => auth()->user()->id,
                    'created_by'              => auth()->user()->firstname . ' ' . auth()->user()->lastname,

                ]);

                $landlord = DB::connection('landlord')->table('black_lists')->insert(

                    [
                        'profile_id'              => $request->profile_id,
                        'id_no'                   => $request->id_no,
                        'mobile_no'               => $request->mobile_no,
                        'blacklist_reason'        => $request->blacklist_reason,
                        'created_by'              => auth()->user()->firstname . ' ' . auth()->user()->lastname,

                    ]
                );


                return response()->json([
                    'message'  => 'Black_List Created Successfully',
                    'data'     =>  $Blacklist,
                ], 201);
            } catch (ValidationException $e) {
                return response()->json([
                    'message' => 'Validation Error',
                    'errors'  => $e->errors(),
                ], 400);
            }
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
        //
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

        //   $this->authorize('edit-blacklist');
        if (($request->profile_id == null) || ($request->profile_id != null)) {

            try {
                $request->validate([
                    'blacklist_reason'       => 'required|string',
                ]);

                $Blacklist = Black_List::where('id', $id)->update([
                    'blacklist_reason'        => $request->blacklist_reason,
                ]);

                $Blacklist = Black_List::where('id', $id)->get();
                return response()->json([
                    'message'  => 'Black_List Updated Successfully',
                    'data'     =>  $Blacklist,
                ], 201);
            } catch (ValidationException $e) {
                return response()->json([
                    'message' => 'Validation Error',
                    'errors'  => $e->errors(),
                ], 400);
            }
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function SoftDelete($id)
    {
        // $this->authorize('delete-blacklist');

        $blacklist = Black_List::find($id);

        if ($blacklist) {
            $blacklist->delete();

            $current = Carbon::now();

            $landlord = DB::connection('landlord')->table('black_lists')->where('id', $id)->update([

                'deleted_at'  => $current
            ]);
            return $blacklist;
        } else {
            return null;
        }
    }
}
