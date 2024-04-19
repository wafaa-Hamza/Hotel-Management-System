<?php
namespace App\Repository;

use Illuminate\Support\Facades\DB;
use LucasDotVin\Soulbscription\Models\Plan;
use App\Repositoryinterface\Repositoryplaninterface;
use LucasDotVin\Soulbscription\Enums\PeriodicityType;
use App\Repositoryinterface\Repositoryfeatureinterface;
use App\Http\Controllers\Api\V1\Subscription\PlanController;
//use LucasDotVin\Soulbscription\Models\Feature;
use App\Models\Feature;

class DBrepositoryfeature implements Repositoryfeatureinterface{
    private $featureModel; 

    public function __construct(Feature $featureModel)
    {
        $this->featureModel = $featureModel;
    }
   
    public function featurePagination()
    {
        $features = $this->featureModel::paginate(request()->segment(count(request()->segments())));
        return $features;
    }
    public function index()
    {
        $features = $this->featureModel::get();
        return $features;
    }

    public function geSoftDeletedData()
    {
       $featureTrashed = $this->featureModel::onlyTrashed()->get();

       if($featureTrashed->first()){

            return $featureTrashed;
       }else{
          
            return null;
       }
        
    }
    
    public function restorDataTrashed($id)
    {
        $featureTrashed = $this->featureModel::where('id', $id)->onlyTrashed()->get();
        if ($featureTrashed->first()) {
            $FeatureRestored = $this->featureModel::withTrashed()->where('id', $id)->restore();
            return $featureTrashed;
        } else {
            return null;
        }
    }
    public function store($request)
    {
        $periodicity_type=$request->periodicity_type;
        $this->featureModel::create([ 
            'consumable'       => false,
            'quota'       => false,
            'postpaid'       => false,
            'name'             => $request->name,
            'periodicity_type' => $periodicity_type,
            'periodicity'      => $request->periodicity,
        ]);
        $feature = $this->featureModel::where('name',$request->name)->get();
        return  $feature;
    }

    public function show($id)
    {
       
        $plan = $this->featureModel::where('id',$id)->first();
        return $plan;
    }

    public function update($request, $id)
    {
        
        $periodicity_type=$request->periodicity_type;
        $features = $this->featureModel::where('id', $id)->first();
        if($features){
            $this->featureModel::where('id',$id)->Update([
                'name'  => (!empty($request['name'])) ? $request['name'] :  $features->name,
                'periodicity_type' => (!empty($request->periodicity_type)) ? $request->periodicity_type :  $features->periodicity_type,
                'periodicity'      => (!empty($request->periodicity)) ? $request->periodicity :  $features->periodicity,  
                'consumable'      => (!empty($request->consumable)) ? $request->consumable :  $features->consumable,       
                'quota'      => (!empty($request->quota)) ? $request->quota :  $features->quota,       
                'postpaid'      => (!empty($request->postpaid)) ? $request->postpaid :  $features->postpaid,       

            ]);
            $plan = $this->featureModel::where('id',$id)->get();
            return  $plan;
        }else{
            return null;
        }       
        
    }

    
    public function destroy($id)
    {
        $feature=$this->featureModel::find($id);
        if($feature){
            $feature->delete();
            return $feature;
        }else{
            return null;
        }
    }

    public function DBDelete($id)
    {
        $feature=$this->featureModel::withTrashed()->find($id);
        if($feature){
          DB::table('features')->where('id', $id)->delete();
            return $feature;
        }else{
            return null;
        }

    }

    }



