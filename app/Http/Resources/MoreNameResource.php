<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MoreNameResource extends JsonResource
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
    "first_name" => $this->first_name,
    "last_name" => $this->last_name,
    "id_no" => $this->id_no,
    "mobile" => $this->mobile,
    "birth_date" => $this->birth_date,
    "guest" => $this->guest,
    "profile" => $this->profile,
    "country" => $this->country,
        ];
    }


}
