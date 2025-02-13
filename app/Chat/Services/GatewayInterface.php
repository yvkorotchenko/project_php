<?php

declare(strict_types=1);

namespace App\Chat\Services;

use App\Chat\Entities\Participants\ParticipantInterface;

interface GatewayInterface
{
    public function sendMessage(ParticipantInterface $reciever, array $messageBody): void;
    public function addToGroup(ParticipantInterface $participant, string $groupName): void;
}
