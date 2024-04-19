<?php
namespace App\Repository\profiles\Calculate;

use App\Models\Budget;
use App\Models\JournalVoucherDetail;
use App\Models\JournalVoucherMaster;
use App\Models\JournalVoucherType;
use Illuminate\Support\Facades\DB;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;
use App\Repositoryinterface\Profiles\Calculate\RepositoryJournalVoucherMasterInterface;
use Carbon\Carbon;

class DBRepositoryJournalVoucherMaster implements RepositoryJournalVoucherMasterInterface{
    private $jurnalVoucherMasterModel;
    private $jurnalVoucherDetailsModel;
    private $journalVoucherTypeModel;
 
    public function __construct(JournalVoucherMaster $jurnalVoucherMasterModel, JournalVoucherType $journalVoucherTypeModel,JournalVoucherDetail $jurnalVoucherDetailsModel)
    {
        $this->jurnalVoucherMasterModel = $jurnalVoucherMasterModel;
        $this->journalVoucherTypeModel = $journalVoucherTypeModel;
        $this->jurnalVoucherDetailsModel = $jurnalVoucherDetailsModel;
     
    }

    public function index()
    {
        $jurnalVoucherType = $this->jurnalVoucherMasterModel::get();
        return $jurnalVoucherType;
    }
    public function store($request)
    {
        $jurnalVoucherMaster = $this->jurnalVoucherMasterModel::create([
                'jv_date' => $request['jv_date'],
                'fyear' => Carbon::parse($request['jv_date'])->format('Y'),
                'fperiod' => intval(explode('-',$request['jv_date'])[1]),
                'jv_type_id' => $request['jv_type_id'],
                'jv_no' => $this->getLastSerial()->last_serial_no+1,
                'total_debit' => $request['total_debit'],
                'total_credit' => $request['total_credit'],
                'mdescription' => $request['mdescription'],
                'source_code' => 'PMS',
                'is_auto_jv' => false,
                'is_posted' => false,
                'is_reverse' => false,
                'created_by' => auth()->id(),
                'created_datetime' => now(),
                'last_updated_user_id' => null,
                'Posted_User_Id' => null,
                'Posted_DateTime' => null,
                'Sys_ip' => $request->ip(),
            ]);
        
        return $jurnalVoucherMaster->id;
    }
    public function show($id)
    {
        $jurnalVoucher = $this->jurnalVoucherMasterModel::where('id',$id)
        ->with('journalVoucherType')
        //->with('journalVoucherDetails')
       ->with(['journalVoucherDetails' => function($q)use($id){
          $q->where('jv_master_id', $id);
          $q->with('chartOfAccount');
          return $q;
       }])
        ->get();
        return $jurnalVoucher; 
    }

    public function update($request,$id)
    {
       
         $jurnalVoucherTypeData = $this->jurnalVoucherMasterModel::where('id',$id)->first();
         if($jurnalVoucherTypeData)
         {
            $data = $this->jurnalVoucherMasterModel::where('id',$id)->update([
                'jv_date' => (!empty($request['jv_date'])) ? $request['jv_date'] : $jurnalVoucherTypeData->jv_date,
                'fyear' =>(!empty($request['jv_date'])) ? Carbon::parse($request['jv_date'])->format('Y') :$jurnalVoucherTypeData->fyear ,
                'fperiod' =>(!empty($request['jv_date'])) ? intval(explode('-',$request['jv_date'])[1]) : $jurnalVoucherTypeData->fperiod ,
                'jv_type_id' =>(!empty($request['jv_type_id'])) ? $request['jv_type_id'] : $jurnalVoucherTypeData->jv_type_id,
                'total_debit' => (!$request->has($request['total_debit'])) ? $request['total_debit'] : $jurnalVoucherTypeData->total_debit,
                'total_credit' => (!$request->has($request['total_credit'])) ? $request['total_credit'] : $jurnalVoucherTypeData->total_credit,
                'mdescription' => (!$request->has($request['mdescription'])) ? $request['mdescription'] : $jurnalVoucherTypeData->mdescription,
                'source_code' => (!empty($request['source_code'])) ? $request['source_code'] : $jurnalVoucherTypeData->source_code,
                'is_auto_jv' =>(!empty($request['is_auto_jv'])) ? $request['is_auto_jv'] : $jurnalVoucherTypeData->is_auto_jv,
                'is_posted' =>(!empty($request['is_posted'])) ? $request['is_posted'] : $jurnalVoucherTypeData->is_posted,
                'is_reverse' =>(!empty($request['is_reverse'])) ? $request['is_reverse'] : $jurnalVoucherTypeData->is_reverse,
                'last_updated_user_id' =>auth()->id(),
             ]);
           
           // $jurnalVoucherType = $this->jurnalVoucherMasterModel::where('id',$id)->get();
            return $data;
         }else{
            return null;
         }
        
    }
    public function destroy($id)
    {
        
        $jurnalVoucherType = $this->show($id);
        if($jurnalVoucherType->first())
        {
                $jurnalVoucherType->first()->delete();
            return 'dataDeleted';
        }else{
            return false;
        }
    }

    /**
     * functions to help store
     * 1..to get last_serial from jv_type
     */
    
    public function getLastSerial()
    {
        $lastSerial = $this->journalVoucherTypeModel->latest()->first('last_serial_no');
        if($lastSerial)
        {
            return $lastSerial;
        }else{
            return 0;
        }
    }

}