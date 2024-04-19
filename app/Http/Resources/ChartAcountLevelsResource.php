<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChartAcountLevelsResource extends JsonResource
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
        return[
            'id' => $this->id,
            'level_length' => $this->level_length,
            'level_name' => $this->level_name,
            'level_name_loc' => $this->level_name_loc,
            'level_color' => $this->level_color,
            'ser_gap' => $this->ser_gap,
        ];
    }
}
