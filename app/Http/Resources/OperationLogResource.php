<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OperationLogResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
//        return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'action_en' => $this->action_en,
            'action_zh' => $this->action_zh,
            'type_req' => $this->type_req,
            'path_req' => $this->path_req,
            'path_data' => $this->data_req,
            'ip' => $this->ip,
            'created' => $this->created_at->toDateTimeString(),
        ];
    }
}
