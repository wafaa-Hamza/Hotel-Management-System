<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RateCodeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rate_code' => $this->rate_code,
            'name' => $this->name,
            'name_loc' => $this->name_loc,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'active' => $this->active,
            'meal_id' => $this->meal_id,
            'meal' => $this->meal,
            //'meal_package_id' => $this->meal_package_id,
            'meal_package' =>$this->meal_package,
            'room_types' => $this->roomTypes,
        ];
    }
}