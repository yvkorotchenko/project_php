<?php

namespace App\Chat\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuestionType extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $name = $this->name_local;
        if ($request->header('Accept-Language', 'zn') === 'en') {
            $name = $this->name;
        }

        return [
            'id' => $this->id,
            'name' => $name,
        ];
    }
}
