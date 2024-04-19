<?php
namespace App\Repository\profiles\Calculate;

use App\Models\Budget;
use Illuminate\Support\Facades\DB;
use Symfony\Component\CssSelector\XPath\Extension\FunctionExtension;
use App\Repositoryinterface\Profiles\Calculate\RepositoryBudgetInterface;

class DBRepositoryBudget implements RepositoryBudgetInterface{
    private $budgetModel;
 
    public function __construct(Budget $budgetModel)
    {
        $this->budgetModel = $budgetModel;
     
    }

    public function index()
    {
        $budgetData = $this->budgetModel::with('ledger')->get();
        return $budgetData;
    }
    public function store($request)
    {
         foreach($request->budgets as $request)
         {
            $data = $this->budgetModel::create([
                'ledger_id' => $request['ledger_id'],
                'year' => $request['year'],
                'one' =>(!empty($request['one']))? $request['one'] : 0,
                'tow' =>(!empty($request['tow']))? $request['tow'] : 0,
                'three' =>(!empty($request['three']))? $request['three'] : 0,
                'four' =>(!empty($request['four']))? $request['four'] : 0,
                'five' =>(!empty($request['five']))? $request['five'] : 0,
                'six' =>(!empty($request['six']))? $request['six'] : 0,
                'seven' =>(!empty($request['seven']))? $request['seven'] : 0,
                'eight' =>(!empty($request['eight']))? $request['eight'] : 0,
                'nine' =>(!empty($request['nine']))? $request['nine'] : 0,
                'ten' =>(!empty($request['ten']))? $request['ten'] : 0,
                'eleven' =>(!empty($request['eleven']))? $request['eleven'] : 0,
                'twelve' =>(!empty($request['twelve']))? $request['twelve'] : 0,
            ]);
        }
   
        $budgetData = $this->budgetModel::
        where('year', $data->year)->get();
        return $budgetData;
    }
    public function show($year)
    {
        $budgetData = $this->budgetModel::where('year',$year)->with('ledger')->get();
        return $budgetData;
   
    }

    public function update($request)
    {
        $budgetData = $this->budgetModel::where('year',$request->budgets[0]['year'])->first();
        // $data = $this->budgetModel::where('id',$id)->update([
        //     'ledger_id' =>(!empty($request->ledger_id))? $request->ledger_id : $budgetData->ledger_id,
        //     'year' =>(!empty($request->year))? $request->year : $budgetData->year,
        //     'one' =>(!empty($request['one']))? $request['one'] : $budgetData->one,
        //     'tow' =>(!empty($request['tow']))? $request['tow'] : $budgetData->tow,
        //     'three' =>(!empty($request['three']))? $request['three'] : $budgetData->three,
        //     'four' =>(!empty($request['four']))? $request['four'] : $budgetData->four,
        //     'five' =>(!empty($request['five']))? $request['five'] : $budgetData->five,
        //     'six' =>(!empty($request['six']))? $request['six'] : $budgetData->six,
        //     'seven' =>(!empty($request['seven']))? $request['seven'] : $budgetData->seven,
        //     'eight' =>(!empty($request['eight']))? $request['eight'] : $budgetData->eight,
        //     'nine' =>(!empty($request['nine']))? $request['nine'] : $budgetData->nine,
        //     'ten' =>(!empty($request['ten']))? $request['ten'] : $budgetData->ten,
        //     'eleven' =>(!empty($request['eleven']))? $request['eleven'] : $budgetData->eleven,
        //     'twelve' =>(!empty($request['twelve']))? $request['twelve'] : $budgetData->twelve
        // ]);
        $this->destroy($request->budgets[0]['year']);
        $this->store($request);
       
        $budgetData = $this->budgetModel::where('year',$request->budgets[0]['year'])->with('ledger')->get();

        return $budgetData;
    }
    public function destroy($year)
    {
        
        $budgetData = $this->show($year);
        if($budgetData->first())
        {
            foreach($budgetData as $budget)
            {
                $budget->delete();
            }
            return 'dataDeleted';
        }else{
            return 'notExist';
        }
    }

}