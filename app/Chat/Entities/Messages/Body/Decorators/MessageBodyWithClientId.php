<?php

declare(strict_types=1);

namespace App\Chat\Entities\Messages\Body\Decorators;

use App\Chat\Entities\Messages\Body\MessageBodyInterface;

/**
 * Decarator to add clientId field to message body
 */
class MessageBodyWithClientId implements MessageBodyInterface
{
    private MessageBodyInterface $originMessageBody;
    private string $clientId;

    public function __construct(string $clientId, MessageBodyInterface $messageBody)
    {
        $this->clientId = $clientId;
        $this->originMessageBody = $messageBody;
    }

    public function asArray(): array
    {
        return \array_merge(
            $this->originMessageBody->asArray(),
            ['clientId' => $this->clientId]
        );
    }
}
