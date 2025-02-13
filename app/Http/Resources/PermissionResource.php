<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        \Log::info(print_r('---Log---', true));
//        \Log::info(print_r($this,true));
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sort' => $this->sort,
            'visible' => $this->hidden,
            'parent_id' => $this->parent_id,
//            'create' => $this->created_at->toDateTimeString(),
//            'update' => $this->updated_at->toDateTimeString(),
        ];
    }
}
