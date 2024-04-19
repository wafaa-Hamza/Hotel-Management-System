<?php

namespace App\Http\Controllers\Api\V1\Profiles\Calculates;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryPreChargeInterface;

class PreChargeController extends Controller
{
    private $repositoryPreChargeInterface;
    public function __construct(RepositoryPreChargeInterface $repositoryPreChargeInterface)
    {
        $this->repositoryPreChargeInterface = $repositoryPreChargeInterface;  
    }
    public function storePreChargeData($id)
    {
        $this->authorize('create-precharge');

        $resRateDays = $this->repositoryPreChargeInterface->getPreChargeDAtaForGest($id);
        if($resRateDays->first())
        {
            $this->repositoryPreChargeInterface->addTransactionCollection($resRateDays);

        }

    }
}