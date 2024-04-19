<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GuestProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       
        return [
            'id' => $this->id,
            'group_code' => (!empty($this->group_code)) ? $this->group_code : null,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'id_number' => $this->id_no,
            'mobile' => $this->mobile,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'city' => $this->city,
            'language' => $this->language,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
            'profile_no' => $this->Profile_no,
            'created_by' => !empty($this->user) ?$this->user :null ,
            'updated_by' =>!empty($this->user_updated) ? $this->user_updated :null ,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'country' => !empty($this->country) ? $this->country : $this->country_id,
            
        ];
    }

}
