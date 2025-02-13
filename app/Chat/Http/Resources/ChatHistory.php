<?php

namespace App\Chat\Http\Resources;

use App\MtSports\Http\Resources\Client as ClientResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatHistory extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'chatDate' => $this->chat_date,
            'client' => new ClientResource($this->whenLoaded('client')),
            'customerServiceOperator' => new CustomerServiceOperator($this->whenLoaded('operator')),
            'questionType' => new QuestionType($this->whenLoaded('questionType')),
            'customerService' => new CustomerService($this->whenLoaded('customerService')),
            'messages' => ChatHistoryMessage::collection($this->whenLoaded('messages')),
        ];
    }
}
