<?php

namespace App\Chat\Services\MessageHandlers;

use App\Chat\Entities\Enum\MessageTypeEnum;
use App\Chat\Entities\Messages\Body\MessageBody;
use App\Chat\Entities\Messages\IncomingMessage;
use App\Chat\Entities\Messages\Message;
use App\Chat\Entities\Messages\MessageCollection;
use App\Chat\Entities\Participants\Participant;
use App\Chat\Services\Gateway;
use Illuminate\Support\Facades\Validator;

class TimeoutLimit implements MessageHandlerInterface
{
    public function __construct(private Gateway $gateway)
    {}

    public function handle(IncomingMessage $incomingMessage): MessageCollection
    {
        $messageData = $incomingMessage->asArray();
        Validator::validate($messageData, [
            'clientId' => 'required|string',
            'content' => 'string',
        ]);
        $client = new Participant($messageData['clientId']);

        return new MessageCollection([
            new Message(
                new MessageBody(
                    MessageTypeEnum::MESSAGE_TYPE_TIMEOUT_LIMIT,
                    $messageData['content']
                ),
                $client,
                $this->gateway
            )
        ]);
    }
}