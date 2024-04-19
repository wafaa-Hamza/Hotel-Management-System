<?php

namespace App\Console;

use App\Console\Commands\ScheduleCommand;
use App\Models\Guests;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Models\Tenant;
use Illuminate\Console\Scheduling\Schedule;
use App\Repository\Rooms\DBrepositoryDayCloseProces;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Repositoryinterface\Rooms\RepositoryDayCloseProcesInterface;

class Kernel extends ConsoleKernel
{

//    private $dayCloseProcesInteface;
//    public function __construct(RepositoryDayCloseProcesInterface $dayCloseProcesInteface)
//     {
//         //parent::__construct();

//         $this->dayCloseProcesInteface =$dayCloseProcesInteface;
//     }
   
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        
        // $schedule->command('hotelDate:schedule-command')->dailyAt('10:30');
         $schedule->command('hotelDate:schedule-command')->everyMinute();
       // $schedule->command('hotelDate:schedule-command')->dailyAt('14:15');
        
        

         //Get a list of tenant database connections
   //$connections = DB::connection('landlord')->table('tenants')->get();

    // Schedule a task for each tenant database connection
   // Get a list of tenant database connections
  // $connections = Tenant::query()->pluck('database')->toArray();

   // Schedule a task for each tenant database connection
  

   //$this->dayCloseProcesInteface->testSchedual()->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {

        //$this->load(__DIR__.'/Commands');
      //  $this->load('hotelDate:schedule-command');
      

        require base_path('routes/console.php');
    }
    protected $commands = [
        Commands\ScheduleCommand::class,
        \App\Console\Commands\testCommmand::class,

        ];
}
