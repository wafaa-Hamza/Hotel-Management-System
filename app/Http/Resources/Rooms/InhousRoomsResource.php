<?php

namespace App\Http\Resources\Rooms;

use App\Http\Resources\GuestInhouseResource;
use Illuminate\Http\Resources\Json\JsonResource;

class InhousRoomsResource extends JsonResource
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
            'room_id' => $this->id,
            'room_name_en' => $this->rm_name_en,
            'room_name_loc' => $this->rm_name_loc,
            'guests' =>$this->whenLoaded('guests',function(){return [
            'guest_id' => $this->guests->id,
            'window' => $this->guests->window,
            'profile' => $this->guests->profile
            
            ];}),
        ];

        }
}
