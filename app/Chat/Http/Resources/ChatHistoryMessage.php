<?php

namespace App\Chat\Http\Resources;

use App\Chat\Services\StorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatHistoryMessage extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $storageService = resolve(StorageService::class);
        return [
            'id' => $this->id,
            'from' => $this->from,
            'content' => $this->message_content,
            'date' => $this->created_at,
            'media' => $this->media_uri ?
            [
                'url' => $this->media_uri,
                'mime' =>  $storageService->getFileExtension(
                    $storageService->getLocalFilePathByUrl(
                        $this->media_uri
                    )
                ),
            ] : null,
        ];
    }
}
