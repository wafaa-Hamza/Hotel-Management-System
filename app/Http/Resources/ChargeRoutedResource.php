<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChargeRoutedResource extends JsonResource
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
            'guestID_from' => $this->guestFrom,
            'ledgerID' => $this->ledger,
            'guestID_to' =>$this->guestTo,
            'windowFrom' =>$this->windowFrom,
            'windowTo' =>$this->windowTo,
        ];
    }
}
