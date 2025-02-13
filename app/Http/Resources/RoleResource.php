<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_zh' => $this->name_zh,
            'permissions' => PermissionResource::collection($this->permissions),
            'alias' => $this->alias,
            'create' => $this->created_at->toDateTimeString(),
            'update' => $this->updated_at->toDateTimeString(),
        ];
    }
}
