<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Planresource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
    // dd($this->features);       
    if($this->features){
        return [
            'plan_id' => $this->id,
             'grace_days' => $this->grace_days,
             'plan_name' => $this->name,
             'periodicity_of_plan' => $this->periodicity,
             'periodicity_type' => $this->periodicity_type,
             'created_at' => $this->created_at,
             'updated_at' => $this->updated_at,
             'deleted_at' => $this->deleted_at,
             'features' => $this->features,
        ];
     }else{
        return [
            'plan_id' => $this->id,
             'grace_days' => $this->grace_days,
             'plan_name' => $this->name,
             'periodicity_of_plan' => $this->periodicity,
             'periodicity_type' => $this->periodicity_type,
             'plan_price' => $this->plan_price,
             'created_at' => $this->created_at,
             'updated_at' => $this->updated_at,
             'deleted_at' => $this->deleted_at,
        ];
        
     }
       
       
    }
}
