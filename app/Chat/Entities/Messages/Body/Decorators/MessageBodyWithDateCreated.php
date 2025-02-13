<?php

declare(strict_types=1);

namespace App\Chat\Entities\Messages\Body\Decorators;

use App\Chat\Entities\Messages\Body\MessageBodyInterface;

class MessageBodyWithDateCreated implements MessageBodyInterface
{
    private MessageBodyInterface $originMessageBody;
    private \DateTimeImmutable $created;

    public function __construct(\DateTimeImmutable $created, MessageBodyInterface $originMessageBody)
    {
        $this->originMessageBody = $originMessageBody;
        $this->created = $created;
    }

    public function asArray(): array
    {
        return \array_merge(
            $this->originMessageBody->asArray(),
            ['created' => $this->created->getTimestamp() * 1000],
        );
    }
}
