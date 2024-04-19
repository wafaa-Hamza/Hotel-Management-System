<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BudgetDataOnlyResource extends JsonResource
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
            "id" => $this->id,
            "ledger_id" => $this->ledger,
            "month_id" => $this->month,
            "year" => $this->year,
            "budget_amount" => $this->budget_amount,
        ];
    }
}
