<?php

declare(strict_types=1);

namespace App\Chat\Queue;

use App\Chat\Entities\Participants\ParticipantInterface;

interface QueueInterface
{
    public function add(ParticipantInterface $participant): void;
    public function remove(ParticipantInterface $participant): void;
    public function all(): array;
    public function inQueue(ParticipantInterface $client): bool;
}
