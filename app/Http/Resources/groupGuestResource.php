<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\RateCodeResource;
use App\Http\Resources\Rooms\RoomResource;
use App\Models\MealPackage;
use Illuminate\Http\Resources\Json\JsonResource;

class groupGuestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       // return parent::toArray($request);
       return  [
        'id' => $this->id,
        'folio_no' => $this->folio_no,
        'in_date' => $this->in_date,
        'out_date' => $this->out_date,
        'original_out_date' => $this->original_out_date,
        'no_of_nights' => $this->no_of_nights,
        'rm_rate' => $this->rm_rate,
        'taxes' => $this->taxes,
        'no_of_pax' => $this->no_of_pax,
        'hotel_note' => $this->hotel_note,
        'client_note' => $this->client_note,
        'way_of_payment' => $this->way_of_payment,
        //'profile' => $this->profile,
       // dd($this->groupDetail),
      // dd($this->profile->first_name == 'group_paymaster'),
        'profile' =>($this->profile->first_name == 'group_paymaster') ? $this->groupDetail : $this->profile,
        'company' => $this->company,
        //'room' => $this->room,
        'room' => new RoomResource($this->whenLoaded('room')),
        'roomType' => $this->roomType,
       // 'rate_code' => $this->rateCode,
        'rate_code' =>new RateCodeResource($this->rateCode),
       // 'rate_code' =>RateCodeResource::collection($this->whenLoaded('rateCode')),'rate_code'=>$this->rateCode->only('roomTypes','meal','meal_package'),
        // 'rate_code' =>$this->rateCode->map(function($q){
        //     return
        //     [
        //         'id' => $q->id,
               
        // ];
        // }),
        'market_segment' => $this->marketSegment,
        'source_of_business' => $this->sourceBusiness,
       // 'meal' => $this->meal,
       // 'meal_package' => $this->meal_package,
        'locked'=>$this->locked,
        'created_by' => $this->createdBy,
        'created_inhousGuest_at' => $this->created_inhousGuest_at,
        'checked_out' => $this->checked_out,
        'checkout_by' => $this->checkout_by,
        'checkout_at' => $this->checkout_at,
        'is_reservation' => $this->is_reservation,
        'res_status' => $this->res_status,
        'is_checked_in'=> $this->is_checked_in,
        'ref_no' => $this->ref_no,
        'is_group' => $this->is_group,
        'group_code' => $this->group_code,
        'is_dummy' => $this->is_dummy,
        'is_checked_in' => $this->is_checked_in,
        'Is_PM' => $this->Is_PM,
       // 'res_rate_day' => $this->resRateDay,
        //'onlyName' =>MoreNameResource::collection($this->whenLoaded('moreName')),'onlyName' => $this->moreName->map->only(['first_name','last_name','mobile','email'])->first(),
       // 'moreName' =>MoreNameResource::collection($this->whenLoaded('moreName')),'moreName' => $this->moreName->map->only(['profile_id','mobile','id_no','last_name','first_name','country'])->skip(1)->toArray(),
        'res_rate_day' =>new ResRateDayResource($this->whenLoaded('resRateDay')),
        'windows' =>$this->window,
        'inv_address' =>$this->inv_address,
        'company_name' =>$this->company_name,
        'vat_no' =>$this->vat_no,
       // 'guestGroup' =>groupGuestResource::collection($this->whenLoaded('guestGroup')),'guestGroup'=>$this->guestGroup->map->only(['id','rm_rate','no_of_pax','rateCode','room','roomType'])
      // dd($this->guestGroup->merge($this->guestGroupNullRateCode)),
      // 'guestGroup'=>$this->guestGroup->map(function($q){
       'guestGroup'=>$this->guestGroup->merge($this->guestGroupNullRateCode)->map(function($q){
            return
            [
              //  dd($q->rateCode->roomTypes),
                'id' => $q->id,
                'rm_rate' => $q->rm_rate,
                'no_of_pax'=>$q->no_of_pax,
                //'rateCode' =>$q->rateCode,
                'rateCode' =>$q->rateCode,
                'room' => $q->room,
                'roomType' => $q->roomType
        ];
        })
       

];
       
} 
}
