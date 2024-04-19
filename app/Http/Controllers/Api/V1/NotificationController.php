<?php

namespace App\Http\Controllers\Api\V1;

use App\helpers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\GeneralCollection;
use App\Http\Requests\notificationRequest;
use App\Http\Resources\NotificationResource;
use App\Repositoryinterface\RepositoryNotificationinterface;

class NotificationController extends Controller
{
    use helpers;
    private $notificationInterface;
    
    public function __construct(RepositoryNotificationinterface $notificationInterface)
    {
        $this->notificationInterface=$notificationInterface;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$this->authorize('view-viewgarden');

        $notification = $this->notificationInterface->index();
        if ($notification->first()) {
            return $this->apiResponse(new GeneralCollection($notification, NotificationResource::class), 200);
        } else
            return $this->apiResponse(["message" => __("not found")]);
    }
    public function getLast5()
    {
        //$this->authorize('view-viewgarden');

        $notification = $this->notificationInterface->getLast5();
        if ($notification->first()) {
            return $this->apiResponse(new GeneralCollection($notification, NotificationResource::class), 200);
        } else
            return $this->apiResponse(["message" => __("not found")]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(notificationRequest $request)
    {
        $this->authorize('create-viewgarden');

        $notification = $this->notificationInterface->store($request);
        return $this->apiResponse(new NotificationResource($notification), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $this->authorize('view-viewgarden');

        $notification = $this->notificationInterface->show($id);

        if ($notification) {
            return $this->apiResponse(['data' => collect(new NotificationResource($notification))], 200);
        } else {
            return $this->apiResponse(["message" => __("not found")]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(notificationRequest $request, string $id)
    {
        $this->authorize('edit-viewgarden');

        $notification = $this->notificationInterface->update($request, $id);
        if ($notification) {

            return $this->apiResponse(new GeneralCollection($notification, NotificationResource::class), 200);
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

        $notification = $this->notificationInterface->destroy($id);

        if ($notification) {
            return $this->apiResponse(["message" => __("notification Deleted")], 200);
        } else {
            return $this->apiResponse(['message' => __("not found")]);
        }
    }
}
