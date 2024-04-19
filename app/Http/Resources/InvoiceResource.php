<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'invoice_number' => $this->invoice_number,
            'total_room_charge' => $this->total_room_charge,
            'total_fb_charge' => $this->total_fb_charge,
            'total_other_charge' => $this->total_other_charge,
            'total_payment' => $this->total_payment,
            'total_taxes' => $this->total_taxes,
            'user_id' => $this->user,
            'guest_id' => $this->guest,
            'window_id' => $this->window,
        ];
    }
}
