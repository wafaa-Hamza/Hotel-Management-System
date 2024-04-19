<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Auth\AuthController;
use App\Repositoryinterface\RepositoryTaskDetailsinterface;
use App\Repositoryinterface\Rooms\RepositoryDayCloseProcesInterface;

class ScheduleCommand extends Command
{
 private $dayCloseProcesInteface;
 private $taskInterface;
 private $authController;
 private $taskModel;
   public function __construct(RepositoryDayCloseProcesInterface $dayCloseProcesInteface,AuthController $authController,Task $taskModel,RepositoryTaskDetailsinterface $taskInterface)
    {
        parent::__construct();

        $this->dayCloseProcesInteface =$dayCloseProcesInteface;
        $this->taskInterface =$taskInterface;
        $this->authController =$authController;
        $this->taskModel =$taskModel;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hotelDate:schedule-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      $request= new Request;
            $requestData= [
         'email' => 'kamr944@gmail.com',
         'password' => '123456789',
      ];
      $request->merge($requestData);
      DB::connection()->statement("USE `masa`");
      $databaseName =config('database.connections.tenant.database');
     $authData = $this->authController->login($request);
        $connections = DB::connection('landlord')->table('tenants')->get();
        foreach($connections as $connection)
        {
            $guestExpecteCheckOutIDS =[];
            $guestExpecteCheckInIDS =[];
            $roomOOfOrderIDS =[];
            $prechargecollection =[];
            $allprecharge=collect(["preCharge"=>collect([])]);
            $dbName = $connection->database;
            DB::connection()->statement("USE `$dbName`");
            $this->dayCloseProcesInteface->testSchedual();
             $expectedCheckOutData = $this->dayCloseProcesInteface->getExpectedCheckOut();
             if($expectedCheckOutData)
             {
                foreach($expectedCheckOutData as $guest)
                {
                   array_push($guestExpecteCheckOutIDS,$guest->id);
                }
               $extendStayRequest = ["guest_id" => $guestExpecteCheckOutIDS];               
               $request = new Request;
               $request->merge($extendStayRequest);
               $expectedCheckOutData = $this->dayCloseProcesInteface->extendStay($extendStayRequest);
             }
             /**
              * expeected check in guests 
              */
            $getExpectedCheckInData = $this->dayCloseProcesInteface->getExpectedCheckIn();
            if($getExpectedCheckInData)
            {
                foreach($getExpectedCheckInData as $guest)
                {
                   array_push($guestExpecteCheckInIDS,$guest->id);
                }
                $extendStayNoShowRequest = ["guest_id" => $guestExpecteCheckInIDS];
               $getExpectedCheckInData = $this->dayCloseProcesInteface->noShow($extendStayNoShowRequest);
            }
            /**
             * rooms out of order and services
             */
            $OrderAndService = $this->dayCloseProcesInteface->OrderAndService();
            if($OrderAndService)
            {
                foreach($OrderAndService as $room)
                {
                   array_push($roomOOfOrderIDS,$room->id);
                }
                $oofOrderRequest = ["oordServicesID" => $roomOOfOrderIDS];
                $request = new Request;
                $request->merge($oofOrderRequest);
                //dd($request->oordServicesID);
                $OrderAndService = $this->dayCloseProcesInteface->toExtendOOrdServicesOneDay($request);
            }

            /**
             * precharge transfer to transactions
             */

             $getPrechargeDataTransfearToTransaction = $this->dayCloseProcesInteface->getPrechargeDataTransfearToTransaction();

             if($getPrechargeDataTransfearToTransaction)
             {

                foreach($getPrechargeDataTransfearToTransaction as $precharge)
                {

                  // dd($precharge->amount);
                    $prechargecollection['pre_charge_id'] = $precharge->id;
                    $prechargecollection['guest_id'] = $precharge->guest_id;
                    $prechargecollection['res_id']=null;
                    $prechargecollection['room_id'] = $precharge->guest->room_id;
                    $prechargecollection['hotel_date'] = $precharge->hotel_date;
                    $prechargecollection['window_id'] = $precharge->guest->window->first()->id;
                    $prechargecollection['ledger_id'] = $precharge->ledger->id;
                    $prechargecollection['payment_type_id'] = null;
                    $prechargecollection['charge_amount'] = $precharge->amount;
                    $prechargecollection['payment_amount'] = null;
                    $prechargecollection['trans_type'] = "Example Transaction";/**                   */
                    $prechargecollection['recd_type'] = "Example Transaction";/**                   */
                    $prechargecollection['ref_id'] = null;
                    $prechargecollection['description'] ="Example Transaction";/**                   */
                    $prechargecollection['is_reservation'] = $precharge->guest->is_reservation;
                    $prechargecollection['invoice_id'] = null;
                    $prechargecollection['inc'] = 1;/**                   */
                    $prechargecollection['voided_at'] = null;
                    //$prechargecollection['created_by'] = 1;
                    $allprecharge['preCharge']->push($prechargecollection);
                    $array = $allprecharge->all();
                
                   $request = new Request;
                   $request->merge($array);

                }

               $getPrechargeDataTransfearToTransaction = $this->dayCloseProcesInteface->storeFromPreChargeToTransaction($request);

             }

             /**
              * finalDayClose()
              *will clear setting cache after execute this function
              */

              $finalDayClose = $this->dayCloseProcesInteface->finalDayClose();

              $settings = DB::table('settings')->get();
              cache()->put('settings_'.$connection->id , $settings);

              $tasks = $this->taskModel->get();

//$auth = auth();

        }
        auth()->logout();

       // Select the database by name
// $dbName = 'elzero';
// DB::connection()->statement("USE `$dbName`");

// // Update data in the selected database
// DB::table('client')->where('id', 1)->update(['name' => 'John Doe']);
    }
     
  
    }
