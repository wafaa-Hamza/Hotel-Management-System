<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\Rooms\RoomResource;
use Illuminate\Http\Resources\Json\JsonResource;

class outOfServicesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       // return parent::toArray($request);

        return[
            'id' => $this->id,
            'room' =>new RoomResource ($this->whenLoaded('room')),
            'oord_type' =>$this->oord_type,
            'start_date' =>$this->start_date,
            'end_date' =>$this->end_date,
            'notes' =>$this->notes,
            'created_by' =>$this->created_by,
            'return_by' =>$this->return_by,
            'return_date' =>$this->return_date,
        ];
    }
}