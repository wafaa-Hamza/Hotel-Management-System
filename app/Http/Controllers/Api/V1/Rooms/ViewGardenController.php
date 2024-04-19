<?php

namespace App\Http\Controllers\Api\V1\Rooms;

use App\helpers;
use App\Models\ViewGarden;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ViewGardenRequest;
use App\Http\Resources\GeneralCollection;
use App\Http\Resources\ViewGardenResouece;
use App\Repositoryinterface\Rooms\RepositoryViewGardenInterface;

class ViewGardenController extends Controller
{
    use helpers;
    private $ViewGardenInterface;
    public function __construct(RepositoryViewGardenInterface $ViewGardenInterface)
    {
        $this->ViewGardenInterface=$ViewGardenInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('view-viewgarden');

        $ViewGarden = $this->ViewGardenInterface->index();
        if ($ViewGarden->first()) {
            return $this->apiResponse(new GeneralCollection($ViewGarden, ViewGardenResouece::class), 200);
        } else
            return $this->apiResponse(["message" => __("not found")]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ViewGardenRequest $request)
    {
        $this->authorize('create-viewgarden');

        $ViewGarden = $this->ViewGardenInterface->store($request);
        return $this->apiResponse(new ViewGardenResouece($ViewGarden), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view-viewgarden');

        $ViewGarden = $this->ViewGardenInterface->show($id);

        if ($ViewGarden) {
            return $this->apiResponse(['data' => collect(new ViewGardenResouece($ViewGarden))], 200);
        } else {
            return $this->apiResponse(["message" => __("not found")]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ViewGardenRequest $request, string $id)
    {
        $this->authorize('edit-viewgarden');

        $ViewGarden = $this->ViewGardenInterface->update($request, $id);
        if ($ViewGarden) {

            return $this->apiResponse(new GeneralCollection($ViewGarden, ViewGardenResouece::class), 200);
        } else {
            return $this->apiResponse(["message" => __("not found")]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->authorize('delete-viewgarden');

        $ViewGarden = $this->ViewGardenInterface->destroy($id);

        if ($ViewGarden) {
            return $this->apiResponse(["message" => __("the view Garden Has Been Deleted")], 200);
        } else {
            return $this->apiResponse(['message' => __("not found")]);
        }
    }
}