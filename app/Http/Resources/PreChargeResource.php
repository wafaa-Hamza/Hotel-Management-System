<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreChargeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return[
            'id' => $this->id,
            'guest' =>new GuestInhouseResource($this->guest),
            'hotel_date' => $this->hotel_date,
            'sys_data' => $this->sys_data,
            'guest' =>new GuestInhouseResource( $this->guest),
            'ledger' => $this->ledger,
            'amount' => $this->amount,
            'transaction_collection' => $this->transaction_collection,
            'user' => $this->user,
            'post_next_day' => $this->post_next_day,
        ];
    }
}
