<?php

declare(strict_types=1);

namespace App\Chat\Entities\Participants;

class Participant implements ParticipantInterface
{
    private string $identifier;
    private bool $isGroup;

    public function __construct(string $identifier, bool $isGroup = false)
    {
        $this->identifier = $identifier;
        $this->isGroup = $isGroup;
    }

    public function identifier(): string
    {
        return $this->identifier;
    }

    public function grouped(): bool
    {
        return $this->isGroup;
    }
}
