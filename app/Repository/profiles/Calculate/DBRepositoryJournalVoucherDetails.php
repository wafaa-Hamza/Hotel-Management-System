<?php
namespace App\Repository\profiles\Calculate;

use Carbon\Carbon;
use App\Models\Budget;
use App\Models\JournalVoucherType;
use Illuminate\Support\Facades\DB;
use App\Models\JournalVoucherDetail;
use App\Models\JournalVoucherMaster;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;
use App\Repositoryinterface\Profiles\Calculate\RepositoryJournalVoucherMasterInterface;
use App\Repositoryinterface\Profiles\Calculate\RepositoryJournalVoucherDetailsInterface;

class DBRepositoryJournalVoucherDetails implements RepositoryJournalVoucherDetailsInterface{
    private $jurnalVoucherDetailsModel;
    private $journalVoucherTypeModel;
    private $journalVoucherMasterInterface;
 
    public function __construct(JournalVoucherDetail $jurnalVoucherDetailsModel, JournalVoucherType $journalVoucherTypeModel,
     RepositoryJournalVoucherMasterInterface $journalVoucherMasterInterface)
    {
        $this->jurnalVoucherDetailsModel = $jurnalVoucherDetailsModel;
        $this->journalVoucherTypeModel = $journalVoucherTypeModel;
        $this->journalVoucherMasterInterface = $journalVoucherMasterInterface;
     
    }

    public function index()
    {
        // $jurnalVoucherType = $this->jurnalVoucherTypeModel::get();
        // return $jurnalVoucherType;
    }
    public function store($request,$masterID)
    {
        foreach($request->details as $detail)
        {
                $jurnalVoucherType = $this->jurnalVoucherDetailsModel::create([
                'fyear' => Carbon::parse($request['jv_date'])->format('Y'),
                'fperiod' => intval(explode('-',$request['jv_date'])[1]),
                'jv_type_id' => $request['jv_type_id'],
                'jv_master_id' => $masterID,
                'jv_no' => $this->journalVoucherMasterInterface->getLastSerial()->last_serial_no+1,
                'jv_srl' => $detail['jv_srl'],
                'account_id' => $detail['account_id'],
                'reference' => $detail['reference'],
                'descriptions' => $detail['descriptions'],
                'debit' => $detail['debit'],
                'credit' => $detail['credit'],
            ]); 
        } 
    }
    public function show($id)
    {
        $jurnalVoucherDetail = $this->jurnalVoucherDetailsModel::where('id',$id)
        ->get();
        return $jurnalVoucherDetail;
   
    }

    public function update($request,$id)
    {
        //  $jurnalVoucherTypeData = $this->jurnalVoucherTypeModel::where('id',$id)->first();
        //    $data = $this->jurnalVoucherTypeModel::where('id',$id)->update([
        //     'name' => (!empty($request['name'])) ? $request['name'] : $jurnalVoucherTypeData->name,
        //     'name_loc' =>(!empty($request['name_loc'])) ? $request['name_loc'] : $jurnalVoucherTypeData->name_loc ,
        //     'fyear' =>(!empty($request['fyear'])) ? $request['fyear'] : $jurnalVoucherTypeData->fyear,
        //     'last_serial_no' => 0,
        //     'is_opening' => (!empty($request['is_opening'])) ? $request['is_opening'] : $jurnalVoucherTypeData->is_opening,
        //     'voucher_type' =>(!empty($request['voucher_type'])) ? $request['voucher_type'] : $jurnalVoucherTypeData->voucher_type
        //  ]);
       
        // $jurnalVoucherType = $this->jurnalVoucherTypeModel::where('id',$id)->get();

        // return $jurnalVoucherType;
    }
    public function destroy($id)
    {
        
        $jurnalVoucherDetail = $this->jurnalVoucherDetailsModel::where('jv_master_id',$id)
        ->get();
        if($jurnalVoucherDetail->first())
        {
            foreach($jurnalVoucherDetail as $detail)
            {
                $detail->delete();
            }
            return 'dataDeleted';
        }else{
            return 'notExist';
        }
    }

}
