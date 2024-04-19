<?php

namespace App\Http\Controllers\Api\V1\Profiles\GuestProfile;

use App\helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\windowResource;
use App\Http\Resources\GeneralCollection;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryWindowInterface;

class WindowController extends Controller
{
    use helpers;

    private $windowInterface; 

    public function __construct(RepositoryWindowInterface $windowInterface)
    {
        $this->windowInterface =$windowInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//        $this->authorize('create-window');

        $windows = $this->windowInterface->show($request->guest_id);
        if(!$windows->first()){
            $window = $this->windowInterface->store($request,null,null);
        }else{
            foreach($windows as $window ){
                $lastWindowID = $window->window_number;
            }
           // dd($lastWindowID);
            $window = $this->windowInterface->store($request,null,$lastWindowID);

        }
        return $this->apiResponse(new GeneralCollection($window,windowResource::class),200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        $this->authorize('view-window');

       $window = $this->windowInterface->show($id);
        if($window->first()){
            return $this->apiResponse(new GeneralCollection($window,windowResource::class),200);

        }else
        return $this->apiResponse(["message" => __("not found")],404); 
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
