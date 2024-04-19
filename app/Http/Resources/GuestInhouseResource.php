<?php

namespace App\Http\Resources;

use App\Http\Resources\MoreNameResource;
use App\Http\Resources\Rooms\RoomResource;
use App\Http\Resources\GroupDetailResource;
use Illuminate\Http\Resources\Json\JsonResource;

class GuestInhouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       // dd(new windowResource($this->whenLoaded('window')->first()));
       //dd($request->moreNameData);
       if(request()->segment(count(request()->segments())) == 'guest_filter')
       {
        $array = [
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
           // 'profile' => $this->profile,
          
            //'profile' =>((empty($this->profile->first_name)) ? null : ($this->profile->first_name == 'group_paymaster')) ? $this->groupDetail : $this->profile,
            'profile' =>((empty($this->profile->first_name)) ? null : ($this->profile->first_name == 'group_paymaster')) ? new GroupDetailResource($this->groupDetail) : $this->profile,
            'company' => $this->company,
            'room' => $this->room,
            //'room' => new RoomResource($this->room),
            'roomType' => $this->roomType,
            'rate_code' => $this->rateCode,
            'market_segment' => $this->marketSegment,
            'source_of_business' => $this->sourceBusiness,
           // 'meal' => $this->meal,
           // 'meal_package' => $this->meal_package,
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
            'res_rate_day' => $this->resRateDay,
            //'moreName' => $this->moreName,
            'moreName' =>MoreNameResource::collection($this->whenLoaded('moreName')),'moreName' => $this->moreName->map->only(['profile_id','mobile','id_no','last_name','first_name','country']),
            'res_rate_day' =>new ResRateDayResource($this->whenLoaded('resRateDay')),
            'windows' =>$this->window,
            'inv_address' =>$this->inv_address,
            'company_name' =>$this->company_name,
            'vat_no' =>$this->vat_no,
            'attachment' =>(count($this->attachment) != 0)?$this->attachment : null,
            'res_type' =>$this->res_type,
            'vip' =>$this->vip,
            'locked'=>$this->locked,
            'purpose_of_visit'=>$this->purpose_of_visit,
            'customer_type'=>$this->customer_type,
            'transactions_sum_payment_amount'=>$this->transactions_sum_payment_amount,
            'transactions_sum_charge_amount'=>$this->transactions_sum_charge_amount,
            
            ];

                    return $array;

       }
       if($request->moreNameData && $request->onlyNameData || (!empty($this->profile->id)) ? $this->profile->id == 1 : null && count($this->moreName) > 1)
       {
        $array = [
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
         // 'profile' => $this->profile,
          'profile' => ($this->profile->first_name == 'group_paymaster') ? $this->groupDetail : $this->profile,
          'company' => $this->company,
         // 'room' => $this->room,
          'room' => new RoomResource($this->room),
          'roomType' => $this->roomType,
          'rate_code' => $this->rateCode,
          'market_segment' => $this->marketSegment,
          'source_of_business' => $this->sourceBusiness,
         // 'meal' => $this->meal,
         // 'meal_package' => $this->meal_package,
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
          'res_rate_day' => $this->resRateDay,
          'onlyName' =>MoreNameResource::collection($this->whenLoaded('moreName')),'onlyName' => $this->moreName->map->only(['first_name','last_name','mobile','email'])->first(),
          'moreName' =>MoreNameResource::collection($this->whenLoaded('moreName')),'moreName' => $this->moreName->map->only(['profile_id','mobile','id_no','last_name','first_name','country'])->skip(1)->toArray(),
          'res_rate_day' =>new ResRateDayResource($this->whenLoaded('resRateDay')),
          'windows' =>$this->window,
          'inv_address' =>$this->inv_address,
          'company_name' =>$this->company_name,
          'vat_no' =>$this->vat_no,
          'attachment' =>(count($this->attachment) != 0)?$this->attachment : null,
          'res_type' =>$this->res_type,
          'vip' =>$this->vip,
          'locked'=>$this->locked,
          'scth_transaction_id'=>$this->scth_transaction_id,
          'purpose_of_visit'=>$this->purpose_of_visit,
          'customer_type'=>$this->customer_type,
          ];
          
          return $array;
       }
      if((!empty($this->profile->id)) ? $this->profile->id == 1 : null)
      {
        $array = [
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
            'profile' => ($this->profile->first_name == 'group_paymaster') ? $this->groupDetail : $this->profile,
            'company' => $this->company,
            'room' => new RoomResource($this->room),

           // 'room' => new RoomResource($this->whenLoaded('room')),
            'roomType' => $this->roomType,
            'rate_code' => $this->rateCode,
            'market_segment' => $this->marketSegment,
            'source_of_business' => $this->sourceBusiness,
           // 'meal' => $this->meal,
           // 'meal_package' => $this->meal_package,
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
            'res_rate_day' => $this->resRateDay,
            'onlyName' =>MoreNameResource::collection($this->whenLoaded('moreName')),'onlyName' => $this->moreName->map->only(['first_name','last_name','mobile','email']),
            'res_rate_day' =>new ResRateDayResource($this->whenLoaded('resRateDay')),
            'windows' =>$this->window,
            'inv_address' =>$this->inv_address,
            'company_name' =>$this->company_name,
            'vat_no' =>$this->vat_no,
            'attachment' =>(count($this->attachment) != 0)?$this->attachment : null,
            'res_type' =>$this->res_type,
            'vip' =>$this->vip,
            'locked'=>$this->locked,
            'purpose_of_visit'=>$this->purpose_of_visit,
            'customer_type'=>$this->customer_type,
            ];
      }else{
       // dd($this);
        $array = [
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
           // 'profile' => $this->profile,
          
            //'profile' =>((empty($this->profile->first_name)) ? null : ($this->profile->first_name == 'group_paymaster')) ? $this->groupDetail : $this->profile,
            'profile' =>((empty($this->profile->first_name)) ? null : ($this->profile->first_name == 'group_paymaster')) ? new GroupDetailResource($this->groupDetail) : $this->profile,
            'company' => $this->company,
            'room' => $this->room,
            //'room' => new RoomResource($this->room),
            'roomType' => $this->roomType,
            'rate_code' => $this->rateCode,
            'market_segment' => $this->marketSegment,
            'source_of_business' => $this->sourceBusiness,
           // 'meal' => $this->meal,
           // 'meal_package' => $this->meal_package,
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
            'res_rate_day' => $this->resRateDay,
            //'moreName' => $this->moreName,
            'moreName' =>MoreNameResource::collection($this->whenLoaded('moreName')),'moreName' => $this->moreName->map->only(['profile_id','mobile','id_no','last_name','first_name','country']),
            'res_rate_day' =>new ResRateDayResource($this->whenLoaded('resRateDay')),
            'windows' =>$this->window,
            'inv_address' =>$this->inv_address,
            'company_name' =>$this->company_name,
            'vat_no' =>$this->vat_no,
            'attachment' =>(count($this->attachment) != 0)?$this->attachment : null,
            'res_type' =>$this->res_type,
            'vip' =>$this->vip,
            'locked'=>$this->locked,
            'purpose_of_visit'=>$this->purpose_of_visit,
            'customer_type'=>$this->customer_type,
            
            ];
      }
  
        return $array;
    }
}
// 'user' => new UserResource($this->whenLoaded('user')),
//             'comments' => $this->comments->map->only(['id', 'body']),
