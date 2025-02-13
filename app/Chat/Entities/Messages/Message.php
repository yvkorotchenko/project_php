<?php

declare(strict_types=1);

namespace App\Chat\Entities\Messages;

use App\Chat\Entities\Messages\Body\MessageBodyInterface;
use App\Chat\Entities\Participants\ParticipantInterface;
use App\Chat\Services\GatewayInterface;

class Message implements MessageInterface
{
    private MessageBodyInterface $body;
    private ParticipantInterface $receiver;
    private GatewayInterface $gateway;

    public function __construct(
        MessageBodyInterface $body,
        ParticipantInterface $receiver,
        GatewayInterface $gateway
    ) {
        $this->body = $body;
        $this->receiver = $receiver;
        $this->gateway = $gateway;
    }

    public function send(): void
    {
        $this->gateway->sendMessage(
            $this->receiver,
            $this->body->asArray()
        );
    }

    public function body(): MessageBodyInterface
    {
        return $this->body;
    }
}
