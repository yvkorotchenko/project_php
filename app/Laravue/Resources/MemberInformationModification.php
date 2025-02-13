<?php

namespace App\Laravue\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberInformationModification extends JsonResource
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
