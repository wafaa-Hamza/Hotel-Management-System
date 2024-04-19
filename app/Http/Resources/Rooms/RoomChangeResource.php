<?php

namespace App\Http\Resources\Rooms;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomChangeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return[
            'id'    => $this->id,
            'reason'    => $this->reason,
            'hotel_date'    => $this->hotel_date,
            'guest_id'  => $this->guest,
            'user_id'   => $this->user,
            'from_room_number'  => $this->RoomChangeFrom,
            'to_room_number'    => $this->RoomChangeTo,
        ];
       




    }
}