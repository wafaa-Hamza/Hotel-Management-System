<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExpensesResource extends JsonResource
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
       return[
             "id" => $this->id,
            "exp_ledger" => $this->ledger,
            "amount" => $this->amount,
            "name" => $this->name,
            "file" => $this->file,
            "description" => $this->description,
            "date"=>$this->date,
            "reference"=>$this->reference,
            "hotel_date"=>$this->hotel_date,
       ];
    }
}
