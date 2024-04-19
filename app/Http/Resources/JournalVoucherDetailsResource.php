<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ChartAccountResource;
use App\Http\Requests\ChartOfAccountsRequest;
use App\Http\Resources\JournalVoucherResource;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\JournalVoucherMasterResource;

class JournalVoucherDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
       // return parent::toArray($request);
    //    $this->whenLoaded('guests',function(){return [
    //     'guest_id' => $this->guests->id,
    //     'window' => $this->guests->window
    
    // ];}),
       return[
        'id' => $this->id,
      //  'fyear' => $this->fyear,
       // 'fperiod' => $this->fperiod,
        //'jv_type' => new JournalVoucherResource($this->whenLoaded('journalVoucherType')),
        //'jv_master' =>new JournalVoucherMasterResource($this->whenLoaded('journalVoucherMaster')),
       // 'jv_master' =>$this->journalVoucherMaster,
        'jv_no' => $this->jv_no,
        'jv_srl' => $this->jv_srl,
        'account' =>new ChartAccountResource($this->whenLoaded('chartOfAccount')),
        'reference' => $this->reference,
        'descriptions' => $this->descriptions,
        'debit' => $this->debit,
        'credit' => $this->credit,
       ];
    }
}
