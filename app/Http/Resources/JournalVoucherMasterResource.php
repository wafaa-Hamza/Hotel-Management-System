<?php

namespace App\Http\Resources;

use App\Http\Resources\JournalVoucherDetailsResource;
use App\Http\Resources\JournalVoucherResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JournalVoucherMasterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      //  return parent::toArray($request);
      //'onlyName' =>MoreNameResource::collection($this->whenLoaded('moreName')),'onlyName' => $this->moreName->map->only(['first_name','last_name','mobile','email'])->first(),

      return[
        'id' => $this->id,
        'jv_date' => $this->jv_date,
        'fyear' => $this->fyear,
        'fperiod' => $this->fperiod,
        'jv_type_id' =>new JournalVoucherResource($this->whenLoaded('journalVoucherType')),
        //'details' =>$this->journalVoucherDetails,
        //'details' =>new JournalVoucherDetailsResource($this->whenLoaded('journalVoucherDetails')),
        'details' =>JournalVoucherDetailsResource::collection($this->whenLoaded('journalVoucherDetails')),'details' => $this->journalVoucherDetails->map->only(['id','jv_srl','account','reference','descriptions','credit','debit']),
        'jv_no' => $this->jv_no,
        'mdescription' => $this->mdescription,
        'total_debit' => $this->total_debit,
        'total_credit' => $this->total_credit,
        'source_code' => $this -> source_code,
        'is_auto_jv' => $this -> is_auto_jv,
        'is_posted' => $this -> is_posted,
        'is_reverse' => $this -> is_reverse,
        'created_by' => $this -> createdBy,
        'created_datetime' => $this -> created_datetime,
        'last_updated_user_id' => $this->lastUpdatedUser ,
        'last_Updated_DateTime' => $this -> last_Updated_DateTime,
        'Posted_DateTime' => $this -> Posted_DateTime,
        'Sys_ip' => $this -> Sys_ip
      ];
    }
}
