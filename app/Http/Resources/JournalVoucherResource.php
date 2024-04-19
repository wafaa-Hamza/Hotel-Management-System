<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalVoucherResource extends JsonResource
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
                'name' => $this->name,
                'name_loc' => $this->name_loc,
                'fyear' => $this->fyear,
                'last_serialNo' => $this->last_serialNo,
                'is_opening' => $this->is_opening,
                'voucher_type' => $this->voucher_type
       ];
    }
}