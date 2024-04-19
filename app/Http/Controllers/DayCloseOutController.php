<?php

namespace App\Http\Controllers;

use App\Models\Guests;
use App\Models\GuestOut;
use Illuminate\Http\Request;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestInhouseInterface;

class DayCloseOutController extends Controller
{
    private $guestInhouseInterface; 

    public function __construct(RepositoryGuestInhouseInterface $guestInhouseInterface)
    {
        $this->guestInhouseInterface =$guestInhouseInterface;
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
        //
    }


    public function Move_Guest_To_History(Request  $guests){
        $this->authorize('daycloseout');

        $guests=Guests::where('checked_out',1)->get();
          foreach($guests as $guest){
           // dd($guest->res_no);
           $Guests_History_Table=GuestOut::create([
            'id'                        =>$guest->id,
            'folio_no'                  =>$guest->folio_no,
            'in_date'                   =>$guest->in_date,
            'out_date'                  =>$guest->out_date,
            'original_out_date'         =>$guest->original_out_date,    
            'no_of_nights'              =>$guest->no_of_nights,
            'rm_rate'                   =>$guest->rm_rate,
            'taxes'                     =>$guest->taxes,
            'no_of_pax'                 =>$guest->no_of_pax,
            'hotel_note'                =>$guest->hotel_note,
            'client_note'               =>$guest->client_note,
            'way_of_payment'            =>$guest->way_of_payment,
            'profile_id'                =>$guest->profile_id,
            'company_id'                =>$guest->company_id,
            'room_id'                   =>$guest->room_id,
            'room_type_id'              =>$guest->room_type_id,
            'rate_code_id'              =>$guest->rate_code_id,
            'market_segment_id'         =>$guest->market_segment_id,
            'source_of_business_id'     =>$guest->source_of_business_id,
            'meal_id'                   =>$guest->meal_id,
            'created_by'                =>$guest->created_by,
            'created_inhousGuest_at'    =>$guest->created_inhousGuest_at,
            'checked_out'               =>$guest->checked_out,
            'checkout_by'               =>$guest->checkout_by,
            'checkout_at'               =>$guest->checkout_at,
            'is_reservation'            =>$guest->is_reservation,
            'res_status'                =>$guest->res_status,
            'res_no'                    =>$guest->res_no,
            'is_group'                  =>$guest->is_group,
            'group_code'                =>$guest->group_code,
            'is_dummy'                  =>$guest->is_dummy,
            'Is_PM'                     =>$guest->Is_PM,
            'is_cancel'                 =>$guest->is_cancel,
            'cancel_date'               =>$guest->cancel_date,
            'is_checked_in'             =>$guest->is_checked_in,
            'ref_no'                    =>$guest->ref_no,
            'is_online'                 =>$guest->is_online,
        ]);
         }
$guests=Guests::where('checked_out',1)->forceDelete();
    
     return response()->json([
        'message'=>' Guests History Table Done Successfully',
        'data'      =>$Guests_History_Table
    ]);
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
