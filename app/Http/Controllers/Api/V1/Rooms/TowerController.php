<?php

namespace App\Http\Controllers\Api\V1\Rooms;

use App\helpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\TowerRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\TowerResource;
use App\Http\Resources\GeneralCollection;
use App\Repositoryinterface\Rooms\RepositoryTowerInterface;

class TowerController extends Controller
{
    use helpers;

    private $RepositoryTowerInterface;

    public function __construct(RepositoryTowerInterface $RepositoryTowerInterface)
    {
        $this->RepositoryTowerInterface = $RepositoryTowerInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tower = $this->RepositoryTowerInterface->index();
        if ($tower->first()) {

            return $this->apiResponse(new GeneralCollection($tower, TowerResource::class));
        } else

            return $this->apiResponse(["message" => __("not found")]);
    }

    public function store(TowerRequest $request)
    {

        $tower = $this->RepositoryTowerInterface->store($request);

        return $this->apiResponse(new GeneralCollection($tower, TowerResource::class), 201);
    }

    public function show($id)
    {
        //        $this->authorize('view-room', Room::class);
        $tower = $this->RepositoryTowerInterface->show($id);

        if ($tower) {
            return $this->apiResponse(['data' => collect(new TowerResource($tower))], 200);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }

    public function update(TowerRequest $request, $id)
    {
        //  dd($request);

        $tower = $this->RepositoryTowerInterface->update($request, $id);
        if ($tower) {

            return $this->apiResponse(new GeneralCollection($tower, TowerResource::class), 200);
        } else {
            return $this->apiResponse(["message" => __("not found")], 404);
        }
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //        $this->authorize('delete-room', Room::class);
        $tower = $this->RepositoryTowerInterface->destroy($id);

        if ($tower) {
            return $this->apiResponse(["message" => __("the Tower Has Been Deleted")], 200);
        } else {
            return $this->apiResponse(['message' => __("not found")], 404);
        }
    }


}
