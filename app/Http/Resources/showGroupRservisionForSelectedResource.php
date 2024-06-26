<?php

namespace App\Http\Resources;

use App\Http\Resources\Rooms\RoomResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class showGroupRservisionForSelectedResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
//dd($this->rateCode);
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
            'profile' => (new GuestProfileResource($this->profile))->only([
              "id",
              "Profile_no",
              "first_name",
              "last_name",
              "id_no",
              "mobile",
              "phone",
              "email" ,
              "address",
              "country",
              "city",
              "language",
              "date_of_birth",
              "gender",
              "group_code",
              "deleted_at",
              "created_at",
              "updated_at"
            ]),
            'company' => $this->company,

            'room' =>($this->room)?(new RoomResource($this->room))->only([
              'id',
              'rm_name_en',
              'rm_name_loc',
              'rm_max_pax',
              'rm_phone_no',
              'rm_phone_ext',
              'rm_key_code',
              'rm_key_options',
              'rm_connection',
              'fo_status',
              'rm_active',
              'hk_stauts',
              'sort_order',
              'room_type',
              'floors',
              'selected',
              'viewGardens',
              'roomStatus',
              'sort_order',
            ]):null,
            'marketSegment' => $this->marketSegment,
            'window' =>$this->window,
            //'meal_package' => $this->meal_package,
           // 'meal' => $this->meal,
            'roomType' => $this->roomType,
            'source_of_business' =>$this->sourceBusiness,
            'created_by' =>$this->createdBy,
            'checked_out' =>$this->checked_out,
            'checkout_by' =>$this->checkout_by,
            'checkout_at' =>$this->checkout_at,
            'is_reservation' =>$this->is_reservation,
            'res_status' =>$this->res_status,
            'res_no' =>$this->res_no,
            'is_group' =>$this->is_group,
            'group_code' =>$this->group_code,
            'is_dummy' =>$this->is_dummy,
            'Is_PM' =>$this->Is_PM,
            'is_cancel' =>$this->is_cancel,
            'cancel_date' =>$this->cancel_date,
            'is_checked_in' =>$this->is_checked_in,
            'ref_no' =>$this->ref_no,
            'is_online' =>$this->is_online,
            'is_join' =>$this->is_join,
            'is_connected' =>$this->is_connected,
            'locked' =>$this->locked,
            'vip' =>$this->vip,
            'join_to' =>$this->join_to,
            'vat_no' =>$this->vat_no,
            'res_type' =>$this->res_type,
            'inv_address' =>$this->inv_address,
            'purpose_of_visit' =>$this->purpose_of_visit,          
            'customer_type' =>$this->customer_type,          
            'rateCode' =>new RateCodeResource($this->rateCode),
         'guestGroup' =>$this->guestGroup->map(function($q){
            return[
                'id' =>$q->id,
                'folio_no' =>$q->folio_no,
                'in_date' =>$q->in_date,
                'out_date' =>$q->out_date,
                'original_out_date' =>$q->original_out_date,
                'no_of_nights' =>$q->no_of_nights,
                'rm_rate' =>$q->rm_rate,
                'taxes' =>$q->taxes,
                'no_of_pax' =>$q->no_of_pax,
                'hotel_note' =>$q->hotel_note,
                'client_note' =>$q->client_note,
                'way_of_payment' =>$q->way_of_payment,
                'company' =>$q->company,
                'roomType' => $q->roomType,
                'room' =>($q->room)?(new RoomResource($q->room))->only([
                  'id',
                  'rm_name_en',
                  'rm_name_loc',
                  'rm_max_pax',
                  'rm_phone_no',
                  'rm_phone_ext',
                  'rm_key_code',
                  'rm_key_options',
                  'rm_connection',
                  'fo_status',
                  'rm_active',
                  'hk_stauts',
                  'sort_order',
                  'room_type',
                  'floors',
                  'selected',
                  'viewGardens',
                  'roomStatus',
                  'sort_order',
                ]):null,
               // 'marketSegment' =>$q->marketSegment,
                'meal' =>$q->meal,
                'meal_package' =>$q->meal_package,  
                'source_of_business' =>$q->sourceBusiness,
                'created_by' =>$q->createdBy,
                'checked_out' =>$q->checked_out,
                'checkout_by' =>$q->checkout_by,
                'checkout_at' =>$q->checkout_at,
                'is_reservation' =>$q->is_reservation,
                'res_status' =>$q->res_status,
                'res_no' =>$q->res_no,
                'is_group' =>$q->is_group,
                'group_code' =>$q->group_code,
                'is_dummy' =>$q->is_dummy,
                'Is_PM' =>$q->Is_PM,
                'is_cancel' =>$q->is_cancel,
                'cancel_date' =>$q->cancel_date,
                'is_checked_in' =>$q->is_checked_in,
                'ref_no' =>$q->ref_no,
                'is_online' =>$q->is_online,
                'is_join' =>$q->is_join,
                'is_connected' =>$q->is_connected,
                'locked' =>$q->locked,
                'vip' =>$q->vip,
                'join_to' =>$q->join_to,
                'vat_no' =>$q->vat_no,
                'res_type' =>$q->res_type,
                'inv_address' =>$q->inv_address,
                'purpose_of_visit' =>$q->purpose_of_visit,
                'customer_type' =>$q->customer_type,
                'rateCode' =>($q->rateCode != null)?
                  [
                    'id' =>$q->rateCode->id,
                    'rate_code' =>$q->rateCode->rate_code,
                    'name' =>$q->rateCode->name,
                    'name_loc' =>$q->rateCode->name_loc,
                    'meal_id' =>$q->rateCode->meal_id,
                    'meal_package_id' =>$q->rateCode->meal_package_id,
                   // 'start_date' =>$q->start_date,
                   // 'end_date' =>$q->end_date,
                //    'active' =>$q->active,
                  //  'ledger_id' =>$q->ledger_id,
                    'room_types' => [
                        'id'=>$q->room_type_id,
                        'rm_type_code'=>$q->rm_type_code,
                        'rm_type_name'=>$q->rm_type_name,
                        'rm_type_name_loc'=>$q->rm_type_name_loc,
                        'def_pax'=>$q->def_pax,
                       // 'def_price'=>$q->def_price,
                       // 'no_of_rooms'=>$q->no_of_rooms,
                       // 'rm_type_rentable'=>$q->rm_type_rentable,
                     //  'sort_order'=>$q->sort_order,
                      //  'scth_type_code'=>$q->scth_type_code,
                      //  'def_rate_code'=>$q->def_rate_code,
                        'pivot' =>[
                            'pax1' => $q->pax1,
                            'pax2' => $q->pax2,
                            'pax3' => $q->pax3,
                            'pax4' => $q->pax4,
                            'pax5' => $q->pax5,
                            'pax6' => $q->pax6,
                            'extra_pax' => $q->extra_pax,                 
                        ],
                    ]
                ] : null,
            ];
         }),
         'guestGroupNullRateCode' =>$this->guestGroupNullRateCode->map(function($q){
            return[
                'id' =>$q->id,
                'folio_no' =>$q->folio_no,
                'in_date' =>$q->in_date,
                'out_date' =>$q->out_date,
                'original_out_date' =>$q->original_out_date,
                'no_of_nights' =>$q->no_of_nights,
                'rm_rate' =>$q->rm_rate,
                'taxes' =>$q->taxes,
                'no_of_pax' =>$q->no_of_pax,
                'hotel_note' =>$q->hotel_note,
                'client_note' =>$q->client_note,
                'way_of_payment' =>$q->way_of_payment,
                'company' =>$q->company,
                'roomType' => $q->roomType,
                'room' =>$q->room,
               // 'marketSegment' =>$q->marketSegment,
                'meal' =>$q->meal,
                'meal_package' =>$q->meal_package,  
                'source_of_business' =>$q->sourceBusiness,
                'created_by' =>$q->createdBy,
                'checked_out' =>$q->checked_out,
                'checkout_by' =>$q->checkout_by,
                'checkout_at' =>$q->checkout_at,
                'is_reservation' =>$q->is_reservation,
                'res_status' =>$q->res_status,
                'res_no' =>$q->res_no,
                'is_group' =>$q->is_group,
                'group_code' =>$q->group_code,
                'is_dummy' =>$q->is_dummy,
                'Is_PM' =>$q->Is_PM,
                'is_cancel' =>$q->is_cancel,
                'cancel_date' =>$q->cancel_date,
                'is_checked_in' =>$q->is_checked_in,
                'ref_no' =>$q->ref_no,
                'is_online' =>$q->is_online,
                'is_join' =>$q->is_join,
                'is_connected' =>$q->is_connected,
                'locked' =>$q->locked,
                'vip' =>$q->vip,
                'join_to' =>$q->join_to,
                'vat_no' =>$q->vat_no,
                'res_type' =>$q->res_type,
                'inv_address' =>$q->inv_address,
                'purpose_of_visit' =>$this->purpose_of_visit,
                'customer_type' =>$this->customer_type,
                'rateCode' =>($q->rateCode != null)?
                  [
                    'id' =>$q->rateCode->id,
                    'rate_code' =>$q->rateCode->rate_code,
                    'name' =>$q->rateCode->name,
                    'name_loc' =>$q->rateCode->name_loc,
                    'meal_id' =>$q->rateCode->meal_id,
                    'meal_package_id' =>$q->rateCode->meal_package_id,
                   // 'start_date' =>$q->start_date,
                   // 'end_date' =>$q->end_date,
                //    'active' =>$q->active,
                  //  'ledger_id' =>$q->ledger_id,
                    'room_types' => [
                        'id'=>$q->room_type_id,
                        'rm_type_code'=>$q->rm_type_code,
                        'rm_type_name'=>$q->rm_type_name,
                        'rm_type_name_loc'=>$q->rm_type_name_loc,
                        'def_pax'=>$q->def_pax,
                       // 'def_price'=>$q->def_price,
                       // 'no_of_rooms'=>$q->no_of_rooms,
                       // 'rm_type_rentable'=>$q->rm_type_rentable,
                     //  'sort_order'=>$q->sort_order,
                      //  'scth_type_code'=>$q->scth_type_code,
                      //  'def_rate_code'=>$q->def_rate_code,
                        'pivot' =>[
                            'pax1' => $q->pax1,
                            'pax2' => $q->pax2,
                            'pax3' => $q->pax3,
                            'pax4' => $q->pax4,
                            'pax5' => $q->pax5,
                            'pax6' => $q->pax6,
                            'extra_pax' => $q->extra_pax,                 
                        ],
                    ]
                ] : null,
            ];
         }),
  
    
    ];
 
   }
}
