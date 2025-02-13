<?php

declare(strict_types=1);

namespace App\Chat\Entities\Messages\Body\Decorators;

use App\Chat\Entities\Messages\Body\MessageBodyInterface;

class MessageBodyWithOperatorId implements MessageBodyInterface
{
    private string $operatorId;
    private MessageBodyInterface $originMessageBody;

    public function __construct(string $operatorId, MessageBodyInterface $messageBody)
    {
        $this->operatorId = $operatorId;
        $this->originMessageBody = $messageBody;
    }

    public function asArray(): array
    {
        return \array_merge(
            $this->originMessageBody->asArray(),
            ['operatorId' => $this->operatorId]
        );
    }
}
