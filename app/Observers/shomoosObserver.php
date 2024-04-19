<?php

namespace App\Observers;

use App\Models\Shomoos;
use App\Jobs\ShomoosJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Support\Facades\Artisan;
use App\Repositoryinterface\Integration\ShomoosIntegrationInterface;

class shomoosObserver
{
    private $shomoosIntegrationInterface;
    public function __construct(ShomoosIntegrationInterface $shomoosIntegrationInterface) {
        $this->shomoosIntegrationInterface = $shomoosIntegrationInterface;
    }
    /**
     * Handle the Shomoos "created" event.
     */
    public function created(Shomoos $shomoos): void
    {
 //***********start code test done but not in queue */
        // $dataCreated = $shomoos->getAttributes();
        // $shomoosData = [];
        // $shomoosRequest = json_decode($dataCreated['request']);
        // $shomoosData['request'] =  $shomoosRequest;
        // $shomoosData['api'] =  $dataCreated['api'];
        //   $shomoosResponse = $this->shomoosINtegrationInterface-> ($shomoosData);
        //   $shomoosAfterDecode = json_decode($shomoosResponse);
        //   Shomoos::where('id',$dataCreated['id'])->update([
        //     'response_code' =>$shomoosAfterDecode->Status_code,
        //     'response_message' =>$shomoosAfterDecode->Status_description,
        //   ]);
 //***********end code test done but not in queue */
  //$dataCreated = $shomoos->getAttributes();
// if($dataCreated['api'] == 'https://masashomoos.masacloud.net/api/InsertGuest')
//     {
//  //***********start code insert Guest but not in queue */    
          // $shomoosResponse = $this->shomoosINtegrationInterface->InsertGuest($shomoos);
        //   $shomoosAfterDecode = json_decode($shomoosResponse);
        //   Shomoos::where('id',$dataCreated['id'])->update([
        //     'response_code' =>$shomoosAfterDecode->Status_code,
        //     'response_message' =>$shomoosAfterDecode->Status_description,
        //   ]);
//  //***********end code done but not in queue */
// }elseif($dataCreated['api'] == 'https://masashomoos.masacloud.net/api/CheckOutAndRatingGuest')
// {
// //  //***********start code checkOut Guest but not in queue */
//         $dataCreated = $shomoos->getAttributes();
//         $shomoosData = [];
//         $shomoosRequest = json_decode($dataCreated['request']);
//         $shomoosData['request'] =  $shomoosRequest;
//         $shomoosData['api'] =  $dataCreated['api'];
//           $shomoosResponse = $this->shomoosINtegrationInterface->CheckOutAndRatingGuest($shomoosData,$guestData);
//           $shomoosAfterDecode = json_decode($shomoosResponse);
//           Shomoos::where('id',$dataCreated['id'])->update([
//             'response_code' =>$shomoosAfterDecode->Status_code,
//             'response_message' =>$shomoosAfterDecode->Status_description,
//           ]);
// //  //***********end code done but not in queue */
//  }



       


      //  ShomoosJob::dispatch($shomoos,$this->shomoosINtegrationInterface,Tenant::current());
//         $tenant = Tenant::current();
// dispatch(function () use ($tenant) {
//     $tenant->execute(function () {
//        // DB::connection('tenant');
//       // Log::info(DB::connection('landlord')->statement("USE `masa`"));
//      //   DB::connection('landlord')->statement("USE `masa`");
//         ShomoosJob::class;
//     });
// });
    //    $tenant = Tenant::current();
    //    dispatch(function () use ($tenant) {
    //         $tenant->execute(function () {
    //             ShomoosJob::class;
    //         });
    //     });
        
       // Artisan::call('queue:work');








       //**start test */

       ShomoosJob::dispatch($shomoos,$this->shomoosIntegrationInterface);
      // $shomoosResponse = $this->shomoosIntegrationInterface->InsertGuest($shomoos);
    //    $v ='php -r "' .  Shomoos::where('id',17)->update([
    //     'response_code' =>"PsP",
    //     'response_message' =>"pp",
    //    ]). ';"';
    //    $v = $v . ' > /dev/null 2>&1 &';
    //    shell_exec($v);




//     $dataCreated = $shomoos->getAttributes();
//     $shomoosData = [];
//    $shomoosRequest = json_decode($dataCreated['request']);
//    $shomoosData['request'] =  $shomoosRequest;
//    $shomoosData['api'] =  $dataCreated['api'];
//      $shomoosResponse = $this->shomoosINtegrationInterface->getNationality($shomoosData);
//      $shomoosAfterDecode = json_decode($shomoosResponse);
//      Shomoos::where('id',$dataCreated['id'])->update([
//        'response_code' =>$shomoosAfterDecode->Status_code,
//        'response_message' =>$shomoosAfterDecode->Status_description,
//      ]);
       //**end test */
    }

    /**
     * Handle the Shomoos "updated" event.
     */
    public function updated(Shomoos $shomoos): void
    {
        //
    }

    /**
     * Handle the Shomoos "deleted" event.
     */
    public function deleted(Shomoos $shomoos): void
    {
        //
    }

    /**
     * Handle the Shomoos "restored" event.
     */
    public function restored(Shomoos $shomoos): void
    {
        //
    }

    /**
     * Handle the Shomoos "force deleted" event.
     */
    public function forceDeleted(Shomoos $shomoos): void
    {
        //
    }
}
