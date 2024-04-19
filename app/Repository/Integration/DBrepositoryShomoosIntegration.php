<?php

namespace App\Repository\Integration;

use App\helpers;
use App\Models\Shomoos;
use App\Traits\ShomoosTrait;
use App\Repositoryinterface\Integration\ShomoosIntegrationInterface;
use App\Repositoryinterface\Integration\ShomoosInterface;

class DBrepositoryShomoosIntegration implements ShomoosIntegrationInterface
{
    use helpers,ShomoosTrait;
    private $shomoosModel;
    private $ShomoosInterface;
   
    public function  __construct(Shomoos $shomoosModel,ShomoosInterface $ShomoosInterface)
    {
        $this->shomoosModel = $shomoosModel;
      
    }

    public function handelShomoosData($guestData)
    {
        $shomoosBody = $this->handelBodyForGetNAtionality();
        $shomoosRequest = $this->handelData($guestData,$shomoosBody);
        return $shomoosRequest;
    }
    public function handelShomoosDataForStore($guestData,$api)
    {
        if($api == 'https://masashomoos.masacloud.net/api/InsertGuest')
            {
                $shomoosBody = $shomooBody = $this->handelBodyForInsertGuest($guestData);
            }elseif($api == 'https://masashomoos.masacloud.net/api/GetNationalities'){
                $shomoosBody = $this->handelBodyForGetNAtionality();
            }
        $shomoosRequest = $this->handelDataForStore($guestData,$shomoosBody,$api);
        return $shomoosRequest;
    }

    public function handelDataForSendToShomoos($data)
    {
        $shomoosData = [];
        $guestHandeledData = $this->handelShomoosDataForStore($data,$data->api);
        $shomoosResponse = $this->ShomoosInterface->store($guestHandeledData);
        $shomoosRequest = json_decode($data->request);
        $shomoosData['request'] =  $shomoosRequest; 
        $shomoosData['api'] =  $data->api;
    }

    // public function getNationality($request)
    // {
    //     $shomoosHeader = $this->handelHeaderData($header);
    //     $shomoosBody = $this->handelBodyForGetNAtionality();
    //     $api = 'https://masashomoos.masacloud.net/api/GetNationalities';
    //     $shomoos = $this->sendShomoosRequest($request,$header,$shomoosBody,$api);
    //     return $shomoos ;
    // }
    public function getNationality($request)
    {
        $shomoos = $this->sendShomoosRequest($request);
        return $shomoos ;
    }

    public function InsertGuest($shomoosTableRowInserted)
    {
        // $header = [

        //     "RequestId"=> "555",
        //     "UserId"=> "2315331914",
        //     "BranchCode"=> "3-5850053835-288263",
        //     "BranchSecret"=> "qv2wBD-7odhTFNH9_tPKCA2",
        //     "Lang"=> "ar"
        // ];
      
        //$shomooBody = $this->handelBodyForInsertGuest($shomoosTableRowInserted->guest);
        //$shomoosRequest = $this->handelData($shomoosTableRowInserted,$shomooBody);
        $shomoos = $this->sendShomoosRequest($shomoosTableRowInserted);
        if($shomoos->json() == null)
        {
            Shomoos::where('id',$shomoosTableRowInserted['id'])->update([
                'response_code' =>'500',
                'response_message' =>'erro from local : can not connect to shomoos',
              ]);
            return ['message' => "erro with connect to shomoos"];
        }
        $shomoosAfterDecode = json_decode($shomoos);
        Shomoos::where('id',$shomoosTableRowInserted['id'])->update([
            'response_code' =>$shomoosAfterDecode->Status_code,
            'response_message' =>$shomoosAfterDecode->Status_description,
          ]);
        return $shomoos ;
    }
    public function CheckOutAndRatingGuest($request,$guestData)
    {
        $shomooBody = $this->handelBodyForCheckOutAndRatingGuest($request->guest);
        $shomoosRequest = $this->handelData($guestData,$shomooBody);
        $shomoos = $this->sendShomoosRequest($shomoosRequest);
        return $shomoos ;
    }
 
}
