<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FeatureRsource extends JsonResource
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
            'feature_id' => $this->id,
            'periodicity_of_feature' => $this->periodicity,
            'feature_name' => $this->name,
            'consumable_status' => $this->consumable,
            'feature_created_at' => $this->created_at,
            'periodicity_type' => $this->periodicity_type,
            'consumable'       => $this->consumable == 0? 'not_consumable' : 'consumable',
            'quota'       => $this->quota == 0? 'avelable' : 'not_avelable',
            'postpaid'       => $this->postpaid == 0? 'not_paied' : 'paied',
        ];
    }
}
