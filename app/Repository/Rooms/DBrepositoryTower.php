<?php

namespace App\Repository\Rooms;

use App\Models\Tower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositoryinterface\Rooms\RepositoryTowerInterface;

class DBrepositoryTower implements RepositoryTowerInterface
{
    private $TowerModel;


    public function __construct(Tower $TowerModel)
    {
        $this->TowerModel = $TowerModel;
    }

    public function index()
    {
        $tower = $this->TowerModel->get();
        return $tower;
    }

    public function store($request)
    {
        $createTower = $this->TowerModel::create([
            'name'       => $request->name,
            'name_loc'             => $request->name_loc,
        ]);
        $tower =  $this->TowerModel::where('id', $createTower->id)->get();
        return  $tower;
    }

    public function show($id)
    {
        $tower = $this->TowerModel::where('id', $id)->first();
        return $tower;
    }
    public function update($request, $id)
    {
        $periodicity_type = $request->periodicity_type;
        //    dd(empty($request['room_connection']));
        $tower = $this->TowerModel::where('id', $id)->first();
        if ($tower) {
            $this->TowerModel::where('id', $id)->Update([
                'name'  => (!empty($request['name'])) ? $request['name'] : $tower->name,
                'name_loc'  => (!empty($request['name_loc'])) ? $request['name_loc'] : $tower->name_loc,
            ]);
            $tower = $this->TowerModel::where('id', $id)->get();
            return  $tower;
        } else {
            return null;
        }
    }
    public function destroy($id)
    {
        $tower = $this->TowerModel::find($id);
        if ($tower) {
            $tower->delete();
            return $tower;
        } else {
            return null;
        }
    }
  
}
