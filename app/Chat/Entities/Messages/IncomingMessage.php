<?php

declare(strict_types=1);

namespace App\Chat\Entities\Messages;

use App\Chat\Entities\Participants\Participant;
use App\Chat\Entities\Participants\ParticipantInterface;

class IncomingMessage
{
    private ParticipantInterface $sender;
    private array $body;

    public function __construct(string $senderIdnet, array $messageBody)
    {
        $this->sender = new Participant($senderIdnet);
        $this->body = $messageBody;
    }

    public function sender(): ParticipantInterface
    {
        return $this->sender;
    }

    public function type(): string
    {
        return $this->body['type'] ?? '';
    }

    public function asArray(): array
    {
        return $this->body;
    }
}
