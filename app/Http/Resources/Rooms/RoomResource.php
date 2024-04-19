<?php

namespace App\Http\Resources\Rooms;

use App\Http\Resources\GuestInhouseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'rm_name_en' => $this->rm_name_en,
            'rm_name_loc' => $this->rm_name_loc,
            'rm_max_pax' => $this->rm_max_pax,
            'rm_phone_no' => $this->rm_phone_no,
            'rm_phone_ext' => $this->rm_phone_ext,
            'rm_key_code' => $this->rm_key_code,
            'rm_key_options' => $this->rm_key_options,
            'rm_connection' => $this->rm_connection,
            'fo_status' => $this->fo_status,
            'rm_active' => $this->rm_active,
            'hk_stauts' => $this->hk_stauts,
            'sort_order' => $this->sort_order,
            'room_type' => $this->room_type,
            'floors' => $this->floors,
            'guests' =>$this->guests,
            'selected'=>false,
            'viewGardens'=>$this->ViewGardens,
            'roomStatus'=>$this->roomStatus,
           // 'room' => new RoomResource($this->whenLoaded('room')),
            
        ];
    }
}