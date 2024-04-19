<?php

namespace App\Http\Controllers\Api\V1\Rooms;

use App\helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\RoomChangeRequest;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\Rooms\RoomChangeResource;
use App\Repositoryinterface\Rooms\RepositoryRoomChangeInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestInhouseInterface;
use App\Models\Room_change_history;

class RoomChangeController extends Controller
{
    use helpers;

    private $roomChangeInterface; 
    private $guestInhouseInterFace; 

    public function __construct(RepositoryRoomChangeInterface $roomChangeInterface,RepositoryGuestInhouseInterface $guestInhouseInterFace)
    {
        $this->roomChangeInterface =$roomChangeInterface;
        $this->guestInhouseInterFace =$guestInhouseInterFace;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $this->authorize('view-roomchange');

        $roomChange = $this->roomChangeInterface->index();
        if($roomChange->first()){
            return $this->apiResponse(new GeneralCollection($roomChange,RoomChangeResource::class),200);
        }else
        return $this->apiResponse(["message" => __("not found")],404); 
    }
    public function roomChangPagination()
    {
//        $this->authorize('view-roomchange');
        $roomChange = $this->roomChangeInterface->roomChangPagination();
        if($roomChange->first()){
            return $this->apiResponse(new GeneralCollection($roomChange,RoomChangeResource::class),200);
        }else
        return $this->apiResponse(["message" => __("not found")],404); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoomChangeRequest $request)
    {
//        $this->authorize('create-roomchange');

        $gusetInhouse = $this->guestInhouseInterFace->show($request->guest_id);
        if( $gusetInhouse->is_checked_in == 1){
            $roomChange = $this->roomChangeInterface->store($request,$gusetInhouse);

            $requests=['room_id' =>$request->to_room_number,'guest_id' =>$request->guest_id];
            $data = new Request;
            $data->merge($requests);
            $roomUpdate = $this->guestInhouseInterFace->update($data,$requests['guest_id']);
           // dd($roomUpdate);

            return $this->apiResponse(new GeneralCollection($roomChange,RoomChangeResource::class),201);
    
              }else{
                return $this->apiResponse(["message" => __("Guest Must Be checked In")]); 
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
