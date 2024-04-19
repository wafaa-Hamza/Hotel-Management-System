<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BudgetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            "id"            => $this->id,
            "ledger_id"     => $this->ledger,
            "year"          => $this->year,
            "one"          => $this->one,
            "tow"          => $this->tow,
            "three"          => $this->three,
            "four"          => $this->four,
            "five"          => $this->five,
            "six"          => $this->six,
            "seven"          => $this->seven,
            "eight"          => $this->eight,
            "nine"          => $this->nine,
            "ten"          => $this->ten,
            "eleven"          => $this->eleven,
            "twelve"          => $this->twelve,
        ];
        // $arr=[];
        // dd($this);
        // foreach($this['ledger_id'] as $ledgerId)
        // {
        //    // dd($ledgerId->ledger_id);
        //    // dd( $singleData->budget_amount);
        //    array_push($data,['ledger_id' => $ledgerId]);
        //     foreach($ledgerId['data'] as $singleData) {
        //         $data = [
        //            // "ledger_id"     =>  $singleData['ledger_id'],
        //             "month"      =>  $singleData->month->name,
        //             "budget_amount" =>  $singleData->budget_amount
        //         ];
        //     }
        // }
        // array_push($arr,$data);
       // dd($this['data']);
     
        return $data;
    }
}
