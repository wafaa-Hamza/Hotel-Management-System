<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class windowResource extends JsonResource
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
       
        'window_number'=> $this->window_number,
        'window_name'=> $this->window_name,
        'invoice_number'=> $this->invoice_number,
        'guest' => $this->guest,

       ];
    }
}
