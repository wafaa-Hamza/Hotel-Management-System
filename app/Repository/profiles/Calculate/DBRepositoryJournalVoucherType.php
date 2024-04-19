<?php
namespace App\Repository\profiles\Calculate;

use App\Models\Budget;
use App\Models\JournalVoucherType;
use Illuminate\Support\Facades\DB;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;
use App\Repositoryinterface\Profiles\Calculate\RepositoryJournalVoucherTypeInterface;

class DBRepositoryJournalVoucherType implements RepositoryJournalVoucherTypeInterface{
    private $jurnalVoucherTypeModel;
 
    public function __construct(JournalVoucherType $jurnalVoucherTypeModel)
    {
        $this->jurnalVoucherTypeModel = $jurnalVoucherTypeModel;
     
    }

    public function index()
    {
        $jurnalVoucherType = $this->jurnalVoucherTypeModel::get();
        return $jurnalVoucherType;
    }
    public function store($request)
    {
   
            $jurnalVoucherType = $this->jurnalVoucherTypeModel::create([
                'name' => $request['name'],
                'name_loc' => $request['name_loc'],
                'fyear' => $request['fyear'],
                'last_serialNo' => 0,
                'is_opening' => $request['is_opening'],
                'voucher_type' => $request->voucher_type
            ]);
        
   
        $jurnalVoucherType = $this->jurnalVoucherTypeModel::
        where('id', $jurnalVoucherType->id)->get();
        return $jurnalVoucherType;
    }
    public function show($id)
    {
        $jurnalVoucherType = $this->jurnalVoucherTypeModel::where('id',$id)->get();
        return $jurnalVoucherType;
   
    }

    public function update($request,$id)
    {
         $jurnalVoucherTypeData = $this->jurnalVoucherTypeModel::where('id',$id)->first();
           $data = $this->jurnalVoucherTypeModel::where('id',$id)->update([
            'name' => (!empty($request['name'])) ? $request['name'] : $jurnalVoucherTypeData->name,
            'name_loc' =>(!empty($request['name_loc'])) ? $request['name_loc'] : $jurnalVoucherTypeData->name_loc ,
            'fyear' =>(!empty($request['fyear'])) ? $request['fyear'] : $jurnalVoucherTypeData->fyear,
            'last_serial_no' => 0,
            'is_opening' => (!is_null($request['is_opening'])) ? $request['is_opening'] : $jurnalVoucherTypeData->is_opening,
            'voucher_type' =>(!empty($request['voucher_type'])) ? $request['voucher_type'] : $jurnalVoucherTypeData->voucher_type
         ]);
       
        $jurnalVoucherType = $this->jurnalVoucherTypeModel::where('id',$id)->get();

        return $jurnalVoucherType;
    }
    public function destroy($id)
    {
        
        $jurnalVoucherType = $this->show($id);
        if($jurnalVoucherType->first())
        {
                $jurnalVoucherType->first()->delete();
            return 'dataDeleted';
        }else{
            return 'notExist';
        }
    }

}