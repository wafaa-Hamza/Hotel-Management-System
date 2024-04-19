<?php

namespace App\Console\Commands;

use App\Models\Shomoos;
use App\Jobs\ShomoosJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Repositoryinterface\Integration\ShomoosIntegrationInterface;

class testCommmand extends Command
{
    // private $shomoosINtegrationInterface;
    // private $ShomosModle;

    // public function __construct(ShomoosIntegrationInterface $shomoosINtegrationInterface)
    // {
    //     parent::__construct();
    //      $this->shomoosINtegrationInterface = $shomoosINtegrationInterface;
        
    // }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:test-commmand';

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
        $this->info('Running YourJob...');
    //  $dataCreated = $this->ShomosModle->getAttributes();
    //     $shomoosData = [];
    //     $shomoosRequest = json_decode($dataCreated['request']);
    //     $shomoosData['request'] =  $shomoosRequest;
    //     $shomoosData['api'] =  $dataCreated['api'];
    //       $shomoosResponse = $this->shomoosINtegrationInterface->getNationality($shomoosData);
    //       $shomoosAfterDecode = json_decode($shomoosResponse);
        //   Shomoos::where('id',$dataCreated['id'])->update([
        //     'response_code' =>$shomoosAfterDecode->Status_code,
        //     'response_message' =>$shomoosAfterDecode->Status_description,
        //   ]);
        Shomoos::where('id',1)->update([
          'response_code' =>'ww',
          'response_message' =>'ww',
        ]);
        $this->info('YourJob dispatched successfully!');
    }
}
