<?php
namespace App\Repository\profiles\guestProfile;

use Carbon\Carbon;
use App\Models\Discrepancy;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;
use Spatie\Multitenancy\Models\Tenant;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryDiscrepancyInterface;

class DBrepositoryDiscrepancy implements RepositoryDiscrepancyInterface {
    private $DiscrepancyModel;
    private $tenantModel;
    private $settingModel;

    public function __construct(Discrepancy $DiscrepancyModel,Tenant $tenantModel,Setting $settingModel)
    {
        $this->DiscrepancyModel = $DiscrepancyModel;
        $this->tenantModel = $tenantModel;
        $this->settingModel = $settingModel;
    }

    public function index()
    {
        $getAllDiscrepancy = $this->DiscrepancyModel->get();
        return $getAllDiscrepancy;
    }


    public function store($request)
    {
        $tenant_id = $this->tenantModel->current();
        if($tenant_id)
        {
            $hotedDate = cache()->get('settings_'.$tenant_id->id)[0]->hotel_date;
        }else{
            $hotedDate = $this->settingModel->first()->hotel_date;
        }

        $storeChargeRouted = $this->DiscrepancyModel::create(
             [
                'room_id' =>$request->room_id,
                'hotel_date' =>$hotedDate,
                'status_fo' =>$request->status_fo,
                'status_hk' =>$request->status_hk,
                'created_by' =>auth()->user()->id,
            ]
        );


        return  $storeChargeRouted;
    }

    public function show($id)
    {
        $discrepancy = $this->DiscrepancyModel::where('room_id', $id)->where('solved_by',null)->get();
        return $discrepancy;

    }
    public function showByID($id)
    {
        $discrepancy = $this->DiscrepancyModel::where('id', $id)->get();
        return $discrepancy;

    }
    public function update($request, $id)
    {
        $getDiscrepancy = $this->DiscrepancyModel::where('id', $id)->first();
        if($getDiscrepancy){

            $updateDiscrepancy = $this->DiscrepancyModel::where('id',$id)->update(
                [
                    //'room_id' =>(!empty())$request->room_id,
                    //'hotel_date' =>$hotedDate,
                    'status_fo' =>(!empty($request->status_fo)) ? $request->status_fo : $getDiscrepancy->status_fo,
                    'status_hk' =>(!empty($request->status_hk)) ? $request->status_hk : $getDiscrepancy->status_hk,
                    'solved_by' =>auth()->user()->id,
                ]
            );
            $DiscrepancyData = $this->DiscrepancyModel::where('id',$id)->first();
            return  $DiscrepancyData;
        }

     }

    public function destroy($id)
    {

    }

}
