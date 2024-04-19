<?php
namespace App\Repository\profiles\guestProfile;

use App\Models\ChartOfAccount;
use App\Models\ChartOfAccountLevel;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryChartOfAccountsInterface;

class DBrepositoryChartOfAccounts implements RepositoryChartOfAccountsInterface
{
    private $chartOfAccountModel;
    private $chartOfAccountLevelModel;
    public function __construct(ChartOfAccount $chartOfAccountModel,ChartOfAccountLevel $chartOfAccountLevelModel)
    {
        $this->chartOfAccountModel = $chartOfAccountModel;
        $this->chartOfAccountLevelModel = $chartOfAccountLevelModel;
    }
    
    public function index()
    {
        //$chartOfAccountData = $this->chartIOfaccountModel::with(['child','level'])->get();
       $chartOfAccountData = $this->chartOfAccountModel::
      with(['child','level'])->where('main_account_id','=',null)
       ->get();
        return $chartOfAccountData;
    }
    public function indexWithBalance($request)
    {
        //$chartOfAccountData = $this->chartIOfaccountModel::with(['child','level'])->get();
       $chartOfAccountData = $this->chartOfAccountModel::
      with(['child','level'])->where('main_account_id','=',null)
       ->get();
       $balance = $this->chartOfAccountModel::balance(null,$request->startDate,$request->endDate);
       $chartOfAccountData->map(function($item)use($request,$balance){

        $item['balance'] = $balance;
        return $item;
       });
        return $chartOfAccountData;
    }

    // public function indexWithBalance($request)
    // {
    //     //$chartOfAccountData = $this->chartIOfaccountModel::with(['child','level'])->get();
    //     $chartOfAccountData = $this->chartOfAccountModel::with(['child', 'level'])->where('main_account_id', '=', null)
    //         ->get();
    //     $balance = $this->chartOfAccountModel::balance(null, $request->startDate, $request->endDate);
    //     $chartOfAccountData->map(function ($item) use ($request, $balance) {

    //         $item['balance'] = $balance;
    //         return $item;
    //     });
    //     return $chartOfAccountData;
    // }
    public function getAllLevel()
    {
        $levels = $this->chartOfAccountLevelModel->get();
        return  $levels;
    }
    public function store($request)
    {
        $helper = $this->chartOfAccountStoreHelper($request->main_account_id);
        if(array_key_exists('status',$helper)){
            return 'level not found';
        }
        $accountCode = $this->accountCode($helper['accountLevel'],$request->main_account_id);
        //dd($helper['isTransaction']);
        
       $chartOfAccountData = $this->chartOfAccountModel::create([
            'account_code' => $accountCode,
            'main_account_id' => $request->main_account_id,
            'account_name' => $request->account_name,
            'account_name_loc' => $request->account_name_loc,
            'account_level' => $helper['accountLevel'],
            'account_class' =>$helper['accountClass'],
            'account_type' => 1,
            'is_transaction' => $helper['isTransaction'],
            'created_by' => auth()->id(),
        ]);

        $parents = $this->chartOfAccountModel::
        with(['child','level'])->where('main_account_id','=',null)
        ->get();
        return $parents;
    }
    public function chartOfAccountStoreHelper($parentID)
    {
        $return = [];
        $chartOfAccountData = $this->chartOfAccountModel::
       with(['child','level'])->where('id',$parentID)
       ->first();
       $level =  $this->chartOfAccountLevelModel::latest()->first();
      // dd($level->id);
       $accountLevel = $chartOfAccountData->level->id+1;
       $accountClass=  $chartOfAccountData->account_class;
      // dd($level->id);
       if($accountLevel == $level->id)
       {
            $isTransaction= true; 
       }elseif($accountLevel > $level->id){
            $return['status'] = 'level not found';
            return  $return;
       }else{
        $isTransaction= false; 
       }
       $return['accountLevel'] = $accountLevel;
       $return['accountClass'] = $accountClass;
       $return['isTransaction'] = $isTransaction;
       return  $return;
    }
    public function accountCode($accountLevel,$parentID)
    {
       $expesesLevel = $this->chartOfAccountLevelModel::where('id',$accountLevel)->first();
      // dd($expesesLevel);
       $expeses = $this->chartOfAccountModel::where('main_account_id',$parentID)->whereHas('level',function($q)use($accountLevel){
        $q->where('account_level',$accountLevel);
        return $q;
       })->get();
      // dd($expeses->count());
       if($expeses->count() != 0)
       {
        $accountCode =  $expeses->max('account_code')+$expesesLevel->ser_gap;
        return $accountCode;
       }else{
           $firstExpeses = $this->chartOfAccountModel::where('id',$parentID)->get();
      // dd($firstExpeses);
        $accountCode =  $firstExpeses->first()->account_code.str_repeat('0', $expesesLevel->level_length-1).$expesesLevel->ser_gap;
        return $accountCode;
       }
       
        
    }


    public function update($request,$id)
    {
        $response=[];
        $chartOfAccountData = $this->chartOfAccountModel::where('id',$id)->first();
        $chartOfAccountUpdate = $this->chartOfAccountModel::where('id',$id)->update([
             'main_account_id' => ($request->has('main_account_id')) ? $request->main_account_id :$chartOfAccountData->main_account_id ,
             'account_name' => (!empty($request->account_name) ) ? $request->account_name : $chartOfAccountData->account_name,
             'account_name_loc' => (!empty($request->account_name_loc) ) ? $request->account_name_loc : $chartOfAccountData->account_name_loc,
             'account_level' => (!empty($request->account_level) ) ? $request->account_level : $chartOfAccountData->account_level,
             'account_class' => (!empty($request->account_class) ) ? $request->account_class : $chartOfAccountData->account_class,
             'account_type' => (!empty($request->account_type) ) ? $request->account_type : $chartOfAccountData->account_type,
             'is_transaction' => (!empty($request->is_transaction) ) ? $request->is_transaction : $chartOfAccountData->is_transaction,
         ]);
 
         $parents = $this->chartOfAccountModel::
         with(['child','level'])->where('main_account_id','=',null)
         ->get();
         return $parents;
    }

}
