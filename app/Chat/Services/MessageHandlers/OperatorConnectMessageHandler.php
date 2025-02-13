<?php

declare(strict_types=1);

namespace App\Chat\Services\MessageHandlers;

use App\Chat\Entities\Enum\MessageTypeEnum;
use App\Chat\Entities\Messages\Body\Decorators\MessageBodyWithOperatorId;
use App\Chat\Entities\Messages\Body\MessageBody;
use App\Chat\Entities\Messages\IncomingMessage;
use App\Chat\Entities\Messages\Message;
use App\Chat\Entities\Messages\MessageCollection;
use App\Chat\Services\Gateway;

class OperatorConnectMessageHandler implements MessageHandlerInterface
{
    private const OPERATOR_GROUP_NAME = 'operators';

    private Gateway $gateway;

    public function __construct(Gateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function handle(IncomingMessage $incomingMessage): MessageCollection
    {
        $this->gateway->addToGroup($incomingMessage->sender(), self::OPERATOR_GROUP_NAME);

        return new MessageCollection([
            new Message(
                new MessageBodyWithOperatorId(
                    $incomingMessage->sender()->identifier(),
                    new MessageBody(MessageTypeEnum::MESSAGE_TYPE_OPERATOR_CONNECT, ''),
                ),
                $incomingMessage->sender(),
                $this->gateway
            ),
        ]);
    }
}
