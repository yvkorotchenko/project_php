<?php

declare(strict_types=1);

namespace App\Chat\Entities\Messages\Body;

class MessageBody implements MessageBodyInterface
{
    private string $messageType;
    private string $content;

    public function __construct(
        string $messageType,
        string $content
    ) {
        $this->messageType = $messageType;
        $this->content = $content;
    }

    public function asArray(): array
    {
        return [
            'type' => $this->messageType,
            'content' => $this->content,
            'date' => date('Y-m-d H:i:s'),
        ];
    }
}
