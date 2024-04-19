<?php

namespace App\Http\Resources;

use App\Http\Resources\BudgetDataOnlyResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BudgetForGetByYearResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
       // return parent::toArray($request);
     //  dd($this);
     $collection =collect();
//dd($this);
     foreach($this['ledgerData'] as $budget)
     {
        $collection->push(['ledger_id' =>  $budget]);
foreach($this['ledgerData']['data'] as $data)

       // dd($data);
        // dd($this->whenLoaded($data->get()->ledger_id));
         $collection->push(['data' =>  BudgetDataOnlyResource::collection($this->whenLoaded($data)),$data =>$this->data->map->only(['id','month_id','budget_amount'])]);
    }
    //    $data = [
    //     "ledger_id" => $this['ledger_id'],
    //     "data" => BudgetDataOnlyResource::collection($this->whenLoaded('data')),'data' =>$this->data->map->only(['month_id','budget_amount'])
    // ];
    return $collection;
    }
}
