<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionRsource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        //return parent::toArray($request);
        return [
            'permission_id' => $this->id,
            'permission_name' => $this->name,
            'permission_guard_name' => $this->guard_name,
            'permission_created_at' => $this->created_at,
            'permission_updated_at' => $this->updated_at,
        ];
    }
}
