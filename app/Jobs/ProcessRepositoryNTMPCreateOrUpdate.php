<?php

namespace App\Jobs;

use App\Models\Guests;
use App\Repositoryinterface\Integration\IntegrationForNTInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessRepositoryNTMPCreateOrUpdate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $integrationForNTInterface;
    private $guestID;

    /**
     * Create a new job instance.
     */
    public function __construct(IntegrationForNTInterface $integrationForNTInterface, $guestID)
    {
        $this->integrationForNTInterface = $integrationForNTInterface;
        $this->guestID = $guestID;

       // $this->guestModel = $guestModel;

    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {

      $ntmp = $this->integrationForNTInterface->NTMPCreateOrUpdate($this->guestID, 1, 1);
     Guests::where('id', $this->guestID)->update([
            'scth_transaction_id' =>(!empty($ntmp['transaction_id'])) ? $ntmp['transaction_id'] : 'error'// need check

        ]);
    }
}
