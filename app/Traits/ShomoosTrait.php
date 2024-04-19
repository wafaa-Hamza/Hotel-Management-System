<?php
namespace App\Traits;

use App\Models\DetailsIntegration_Table;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Spatie\Multitenancy\Models\Tenant;

trait ShomoosTrait{

    public function handelData($request,$body)
    {
        $shomoosRequest =[];
        $shomoosRequest['Body'] = $body;
        $shomoosRequest['Header'] = $this->handelHeaderData();
       // dump($body);
            $shomoosRequest['pms'] = $this->handelPmsData($request->id);
    
       // $shomoosRequest['pms'] = (!empty($request->id)) ? $this->handelPmsData($request->id) : $this->handelPmsData(1);
        return $shomoosRequest;
    }


    public function handelShomoosTableColumnRequest($shomoosRowInserted)
    {
        $shomoosTableColumnRequest = [];
        $dataCreated = $shomoosRowInserted->getAttributes();
        $shomoosRequest = json_decode($dataCreated['request']);
        $shomoosTableColumnRequest['request'] =  $shomoosRequest;
        $shomoosTableColumnRequest['api'] =  $dataCreated['api'];
        return $shomoosTableColumnRequest;
    }
    public function handelDataForStore($request,$body,$api = null)
    {
       $dataForStore = $this->handelData($request,$body);
       $dataForStore['api'] = $api;
       return $dataForStore;
    }
    public function handelHeaderData()
    {
        $shomoosHeaderData = [];
       $headerData = DetailsIntegration_Table::whereHas('master',function($q){
            $q->where('name','shomoos');
        })->get();
        $shomoosHeaderKeys = [
            'RequestId',
            'UserId' ,
            'BranchCode',
            'BranchSecret' ,
            'Lang',
        ];
        foreach($headerData as $key => $value)
        {
            $value = $value->toArray();
            if(in_array($value['key'],$shomoosHeaderKeys))
            {
                $shomoosHeaderData[$value['key']] = $value['value'];
            }
        }
       // dd($shomoosHeaderData);
        return $shomoosHeaderData;
    }


    public function handelPmsData($guest_id)
    {
        $tenant = Tenant::current();
        $shomoosPmsData = [
            'tenant_id' => (!empty($tenant)) ? $tenant->id : 9999,
            'pms_guest_id' => $guest_id,
        ];
        return $shomoosPmsData;
    }

    /**Start Handek Body */
    public function handelBodyForGetNAtionality()
    {
        $body = [];
        return $body;
    }
    public function handelBodyForInsertGuest($request)
    {
        $body = [
            "DateOfCheckIn" =>$this->handelDate($request->in_date),
            "DateOfCheckOut" =>$this->handelDate($request->out_date),
            "IdentityNum" =>$request->profile->id_no,
            "IdentityType" =>$request->profile->idType->id_type_code ,
            "Nationality" =>$request->profile->country->country_code,//************waiting */
            "DateOfBirth" =>$request->profile->date_of_birth,
            "VersionNumber" =>$request->profile->version_no,//************waiting */
            "RoomNumber" =>(!empty($request->room)) ? $request->room->rm_name_en : null,//************waiting */
            "HaveEscorts" => false,//************waiting */
        ];
        return $body;
    }
    public function handelBodyForCheckOutAndRatingGuest($request)
    {
        $body = [
            "DateOfCheckOut" =>$this->handelDate($request->checkout_at),
            "Accom_Trx_MainId" =>$this->handelDate($request->id),
            "Rating" =>$request->rm_rate, //**whaiting */
        ];
        return $body;
    }




    /**End Handek Body */
    public function handelDate($date)
    {
        $shomoosDate = Carbon::parse($date)->format("m/d/Y");
        return $shomoosDate;
    }
   
    // public function sendShomoosRequest($request,$headerData,$body,$api)
    // {
    //     $tenant = Tenant::current();
    //     $tenantID = ($tenant) ? $tenant->id : 99999;
    //     $requestBody = $this->handelData($request,$tenantID,$body);
    //     $requestBody = json_encode($requestBody);
    //     $sendShomoosRequest = Http::withBody($requestBody,'application/json')->post($api);
    //     return $sendShomoosRequest;
    // }
    public function sendShomoosRequest($request)
    {
        $sendShomoosRequest = Http::withBody($request['request'],'application/json')->post($request['api']);
        return $sendShomoosRequest;
    }


}

