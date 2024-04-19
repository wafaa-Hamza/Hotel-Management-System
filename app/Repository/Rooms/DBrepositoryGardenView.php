<?php
namespace App\Repository\Rooms;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\ViewGarden;
use App\Repositoryinterface\Generalinterface;
use App\Repositoryinterface\Rooms\RepositoryViewGardenInterface;

class DBrepositoryGardenView implements RepositoryViewGardenInterface
{
    private $gardenViwModel;
    public function __construct(ViewGarden $gardenViwModel)
    {
        $this->gardenViwModel = $gardenViwModel;
    }

public function index()
{
    $gardenViwData = $this->gardenViwModel->with('room')->get();
    //  dd($rooms);
    return $gardenViwData;
}
public function store($request)
{
    //dd($request->name_loc);
    $gardenView = $this->gardenViwModel::create([
        'name'       => $request->name,
        'name_loc'             => $request->name_loc,
    ]);
    return $gardenView;
}
public function show($id)
{

    $gardenViwData = $this->gardenViwModel::where('id', $id)->with('room')->first();
    return $gardenViwData;
}
public function update($request, $id)
{
    $gardenView = $this->gardenViwModel::where('id', $id)->first();
    if($gardenView) {
        $this->gardenViwModel::where('id', $id)->Update([
            'name'  => (!empty($request['name'])) ? $request['name'] : $gardenView->name,
            'name_loc'  => (!empty($request['name_loc'])) ? $request['name_loc'] : $gardenView->name_loc,
        ]);
        $gardenViwData = $this->gardenViwModel::where('id', $id)->with('room')->get();
        return  $gardenViwData;
    } else {
        return null;
    }
}
public function destroy($id)
{
    $gardenViw=$this->gardenViwModel::find($id);
    if($gardenViw) {
        $gardenViw->delete();
        return $gardenViw;
    } else {
        return null;
    }
}

}