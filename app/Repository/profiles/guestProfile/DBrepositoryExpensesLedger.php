<?php
namespace App\Repository\profiles\guestProfile;

use App\Http\Requests\GuestProfileRequest;
use App\Models\ExpensesLedger;
use App\Repositoryinterface\Profiles\GuestProfile\RepositoryExpensesLedgerInterface;

class DBrepositoryExpensesLedger implements RepositoryExpensesLedgerInterface{
    private $expensesLedgerModel; 
private $validation;
    public function __construct(ExpensesLedger $expensesLedgerModel)
    {
        $this->expensesLedgerModel = $expensesLedgerModel;
    }

public function index()
{  
    $expensesLedger = $this->expensesLedgerModel::get();
        return $expensesLedger;
    
}
public function show($id)
{  
    $expensesLedger = $this->expensesLedgerModel::where('id', $id)->first();
        return $expensesLedger;
    
}

public function store($request)
{
    $expensesLedger = $this->expensesLedgerModel::create([
        'name' => $request->name,
        'name_loc' => $request->name_loc,
        'gl_acount' => $request->gl_acount
    ]);
    return $expensesLedger;
    /**
     *  {
     "name":"firstExpensesLedgerNames",
     "name_loc":"firstExpensesLedgerNames",
     "gl_acount":"firstExpensesLedgerNames"
        }
     */
}
public function update($request, $id)
{
   $epxpensesLedger = $this->show($id);
   if($epxpensesLedger)
   {
    $this->expensesLedgerModel::where('id',$id)->update([
        'name' =>(!empty($request->name))? $request->name : $epxpensesLedger->name,
        'name_loc' =>(!empty($request->name_loc))? $request->name_loc : $epxpensesLedger->name_loc,
        'gl_acount' =>(!empty($request->gl_acount))? $request->gl_acount : $epxpensesLedger->gl_acount
    ]);
    $epxpensesLedger = $this->show($id);
    return $epxpensesLedger;
   }else{
    return null;
   }
   
}

public function destroy($id)
{
    $epxpensesLedger = $this->show($id);
    if($epxpensesLedger)
    {
       $dataDeleted = $epxpensesLedger->delete();
        return $dataDeleted;
    }else{
        return null;
    }

}




}
