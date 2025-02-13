<?php

namespace App\Chat\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerServiceOperator extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
