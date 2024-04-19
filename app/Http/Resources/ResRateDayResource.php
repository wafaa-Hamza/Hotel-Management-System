<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ResRateDayResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $array=[];
        foreach($this->all() as $data)
        {
            $datas = [
                        "id" =>$data->id,
                        "guest_id" => $data->guest_id,
                        "day_date" => $data->day_date ,
                        "rm_rate" => $data->rm_rate,
                        "rate_code" => $data->rateCode,
                        "meal_id" => $data->meal_id,
                        "meal_package_id" => $data->meal_package_id,
               ];
               array_push($array, $datas);
        }
        return $array;
    }
}
