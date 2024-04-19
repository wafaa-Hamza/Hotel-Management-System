<?php

namespace App\Http\Controllers\Api;

use App\Helper;
use App\Models\Room;
use App\Jobs\testJob;
use App\Models\Guests;
use App\Models\Tenant;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use App\Models\Tenant as ModelsTenant;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use App\Repositoryinterface\Generalinterface;
use Spatie\Multitenancy\Commands\Concerns\TenantAware;
use Spatie\Activitylog\Exceptions\InvalidConfiguration;
use Spatie\Multitenancy\Concerns\UsesMultitenancyConfig;
use App\Repositoryinterface\Integration\ShomoosInterface;
use App\Repositoryinterface\RepositoryTaskDetailsinterface;
use App\Repositoryinterface\Integration\IntegrationForNTInterface;
use App\Repositoryinterface\Integration\ShomoosIntegrationInterface;
use App\Repositoryinterface\Profiles\Calculate\RepositoryDayCloseRecordInterface;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryGuestInhouseInterface;

class TestController  extends Controller
{
    use TenantAware;
    use UsesMultitenancyConfig;
    public $interface;
    public $guestInhousInterface;
    public $RepositoryDayCloseRecordInterface;
    public $IntegrationForNTInterface;
    public $repositoryTaskDetailsinterface;
    public $ShomoosIntegrationInterface;
    public $shomoosInterface;
    public function __construct(
        Generalinterface $interface,
        RepositoryGuestInhouseInterface $guestInhousInterface,
        RepositoryDayCloseRecordInterface $RepositoryDayCloseRecordInterface,
        IntegrationForNTInterface $IntegrationForNTInterface,
        RepositoryTaskDetailsinterface $repositoryTaskDetailsinterface,
        ShomoosIntegrationInterface $ShomoosIntegrationInterface,
        ShomoosInterface $shomoosInterface,
    ) {
        $this->interface = $interface;
        $this->guestInhousInterface = $guestInhousInterface;
        $this->RepositoryDayCloseRecordInterface = $RepositoryDayCloseRecordInterface;
        $this->IntegrationForNTInterface = $IntegrationForNTInterface;
        $this->repositoryTaskDetailsinterface = $repositoryTaskDetailsinterface;
        $this->ShomoosIntegrationInterface = $ShomoosIntegrationInterface;
        $this->shomoosInterface = $shomoosInterface;
    }

    public function testntmp($guest_id, $cuFlag, $transactionTypeId = null, $isUpdate = null)
    {
        dispatch(new testJob());
        //$this->IntegrationForNTInterface->NTMPCreateOrUpdate($guest_id, $cuFlag, $transactionTypeId);

        return 'done';
    }

    public function test(Request $request)
    {



        // $requestTotalRoomCharge=['guest_id' => 1, 'window_id' => [3,2]];
        // $this->interface->guestBalance( $requestTotalRoomCharge);

        //     $requestTotalRoomCharge=['startDate' => '2023-05-01', 'endDate' => '2023-05-04'];
        //    $return = $this->interface->taxes( $requestTotalRoomCharge);
        //     return $return;


        //     $requestTotalRoomCharge=['startDate' => '2023-05-01', 'endDate' => '2023-05-04'];
        //    $return = $this->interface->total( $requestTotalRoomCharge);
        //     return $return;

        // return $this->guestInhousInterface->guestAttachment($request);
        //    $sendMessage = Helper::sendOtpSms('+966550680033','01140177303');
        //    return $sendMessage;

        // $data = Setting::get();
        // return event(new \App\Events\MyEvent($data));
        // $dd = Room::where('id',2)->with('status')->get();
        // return $dd;
        //$balance = Helper::getBalance();

        // return $balance;

        //   $dd = $this->RepositoryDayCloseRecordInterface->guestLedger();
        //   return $dd;



        //Schema::connection($this->tenant->getDatabaseConnection())->createDatabase($this->tenant->getDatabaseName())
        /**
         * to create schema database by code
         */
        //Schema::connection('landlord')->createDatabase('tenant4');//to create database structure in sql server
        // DB::connection()->statement("USE `landlord`");
        // $createNewTenant =  Tenant::create([
        //     'clientname'    =>'tenant4',
        //     'tenantname'    => 'tenant4',
        //     'domain'        => '127.0.1.1',
        //     'database'      => 'tenant4',
        //     'email'   => 'rr',
        //     'language_id'   => 1,
        //     'address'       => 'asdas',
        //     'taxnumber'     =>'asdfd',
        // ]);
        // $connections = DB::connection('landlord')->table('tenants')->where('database','tenant4')->first();
        // $DBName = $connections->database;
        // DB::connection()->statement("USE `$DBName`");
        // $spatieTenant = new \Spatie\Multitenancy\Models\Tenant;

        // $spatieTenant::where('database','tenant4')->first()->execute(function () {
        //     Artisan::call('migrate'); 
        // });
        // return 'done';
        //end create schema database by code
        //return $this->IntegrationForNTInterface->NTMPOccupancyUpdate('s');
       // return $this->repositoryTaskDetailsinterface->lateTime('2023-10-22 15:00', '2023-10-24 20:00');
       //********************************************************************************* */
//        $ipAddress = "192.168.0.1"; // Replace with the IP address of the API
// $endpoint = "https://" . $ipAddress . "/api/floor";
// $url = "https://masaweb.co/api/floor";
// $token = "Bearer your_bearer_token";

// // $response = Http::withHeaders([
// //     'Authorization' => $token,
// // ])->get($endpoint);
// $response = Http::get($url);
// //dd($response);
// if ($response->successful()) {
//     $data = $response->json();
//     return $data;
//     // Process the data as needed
// } else {
//     // Handle the API request failure
//     $statusCode = $response->status();
//     $errorMessage = $response->body();
//     // Handle the error accordingly
// }
/************************************************************************************************* */
    //    $guest = Guests::where('id',5)->first();
    //    $api = 'https://masashomoos.masacloud.net/api/GetNationalities';
    //     $guestHandeledData = $this->ShomoosIntegrationInterface->handelShomoosDataForStore($guest,$api);
    //     $shomoosResponse = $this->ShomoosInterface->store($guestHandeledData);
    //     if($shomoosResponse)
    //     {
    //         $shomoosData = [];
    //        $shomoosRequest = json_decode($shomoosResponse->request);
    //        $shomoosData['request'] =  $shomoosRequest;
    //        $shomoosData['api'] =  $shomoosResponse->api;
    //          $shomoosResponse = $this->ShomoosIntegrationInterface->getNationality($shomoosData);
    //        //  dd($shomoosResponse);
    //     }
    //     return json_decode($shomoosResponse);
    //*************************************************** */
        $guestData = Guests::where('id',1)->first();
    $api = 'https://masashomoos.masacloud.net/api/GetNationalities';
    $guestHandeledData = $this->ShomoosIntegrationInterface->handelShomoosDataForStore($guestData,$api);
    //dd($guestHandeledData);
    $shomoosResponse = $this->shomoosInterface->store($guestHandeledData);
    return $shomoosResponse;

    }
}
