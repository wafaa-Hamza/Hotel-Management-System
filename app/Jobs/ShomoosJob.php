<?php

namespace App\Jobs;

use App\Models\Shomoos;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Queue\InteractsWithQueue;
use Spatie\Multitenancy\Jobs\TenantAware;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Repositoryinterface\Integration\ShomoosIntegrationInterface;

class ShomoosJob implements ShouldQueue, TenantAware
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $shomoosModel;
    protected $shomoosIntegrationInterface;
    protected $tenantCurrent;
    protected $command;
    /**
     * Create a new job instance.
     */
    public function __construct($shomoosModel, ShomoosIntegrationInterface $shomoosIntegrationInterface)
    {
        $this->shomoosModel = $shomoosModel;
        $this->shomoosIntegrationInterface = $shomoosIntegrationInterface;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {

        //*****start original code */
        // $dataCreated = $this->shomoosModel->getAttributes();
        //      $shomoosData = [];
        //     $shomoosRequest = json_decode($dataCreated['request']);
        //     $shomoosData['request'] =  $shomoosRequest;
        //     $shomoosData['api'] =  $dataCreated['api'];

        //       $shomoosResponse = $this->shomoosIntegrationInterface->getNationality($shomoosData);
        //       $shomoosAfterDecode = json_decode($shomoosResponse);
        //       Shomoos::where('id',$dataCreated['id'])->update([
        //         'response_code' =>$shomoosAfterDecode->Status_code,
        //         'response_message' =>$shomoosAfterDecode->Status_description,
        //       ]);
        //*****end original code */




        //**start test */

        //  $dataCreated = $this->shomoosModel->getAttributes();
        // if($dataCreated['api'] == 'https://masashomoos.masacloud.net/api/InsertGuest')
        //     {
        // //  //***********start code insert Guest but not in queue */
        //         //$dataCreated = $shomoos->getAttributes();
        //         $shomoosData = [];
        //         $shomoosRequest = json_decode($dataCreated['request']);
        //         $shomoosData['request'] =  $shomoosRequest;
        //         $shomoosData['api'] =  $dataCreated['api'];
        //           $shomoosResponse = $this->shomoosIntegrationInterface->InsertGuest($shomoosData,$this->shomoosModel);
        //           $shomoosAfterDecode = json_decode($shomoosResponse);
        //           Shomoos::where('id',$dataCreated['id'])->update([
        //             'response_code' =>$shomoosAfterDecode->Status_code,
        //             'response_message' =>$shomoosAfterDecode->Status_description,
        //           ]);
        // //  //***********end code done but not in queue */
        // }elseif($dataCreated['api'] == 'https://masashomoos.masacloud.net/api/CheckOutAndRatingGuest'){
        // //  //***********start code checkOut Guest but not in queue */
        //         $shomoosData = [];
        //         $shomoosRequest = json_decode($dataCreated['request']);
        //         $shomoosData['request'] =  $shomoosRequest;
        //         $shomoosData['api'] =  $dataCreated['api'];
        //           $shomoosResponse = $this->shomoosIntegrationInterface->CheckOutAndRatingGuest($shomoosData,$this->shomoosModel);
        //           $shomoosAfterDecode = json_decode($shomoosResponse);
        //           Shomoos::where('id',$dataCreated['id'])->update([
        //             'response_code' =>$shomoosAfterDecode->Status_code,
        //             'response_message' =>$shomoosAfterDecode->Status_description,
        //           ]);
        // //  //***********end code done but not in queue */
        //  }


        $shomoosResponse = $this->shomoosIntegrationInterface->InsertGuest($this->shomoosModel);

        //**end test */
    }
}
