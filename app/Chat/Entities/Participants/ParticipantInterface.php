<?php

declare(strict_types=1);

namespace App\Chat\Entities\Participants;

interface ParticipantInterface
{
    public function identifier(): string;
    public function grouped(): bool;
}
