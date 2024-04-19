<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ChartAcountLevelsResource;

class ChartAccountResource extends JsonResource
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
        if (!empty($this->child[0])) {
            return [
                "id" => $this->id,
                "label" => $this->account_name,
                "account_name_loc" => $this->account_name_loc,
                "Balance" => (!is_null($this->Balance)) ? $this->Balance : null,
                "TotalDebit" => $this->TotalDebit,
                "TotalCredit" => $this->TotalCredit,
                "OpenDebit" => $this->OpenDebit,
                "OpenCredit" => $this->OpenCredit,
                "account_level" => new ChartAcountLevelsResource($this->whenLoaded('level')),
                "account_class" => $this->account_class,
                "account_code" => $this->account_code,
                "account_type" => $this->account_type,
                "is_transaction" => $this->is_transaction,
                //"children" =>ChartAccountResource::collection($this->child),
                "children" => (!empty($this->child[0])) ? ChartAccountResource::collection($this->child) : null,
                // "children" =>new ChartAccountResource($this->whenLoaded('child')),
            ];
        } else {
            return [
                "id" => $this->id,
                "label" => $this->account_name,
                "account_name_loc" => $this->account_name_loc,
                "Balance" => (!is_null($this->Balance)) ? $this->Balance : null,
                "TotalDebit" => $this->TotalDebit,
                "TotalCredit" => $this->TotalCredit,
                "OpenDebit" => $this->OpenDebit,
                "OpenCredit" => $this->OpenCredit,
                "account_level" => new ChartAcountLevelsResource($this->whenLoaded('level')),
                "account_class" => $this->account_class,
                "account_code" => $this->account_code,
                "account_type" => $this->account_type,
                "is_transaction" => $this->is_transaction,

            ];
        }
    }
}
