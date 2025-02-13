<?php

declare(strict_types=1);

namespace App\Chat\Services;

use App\Chat\Entities\Participants\ParticipantInterface;
use GatewayWorker\Lib\Gateway as LibGateway;

class Gateway implements GatewayInterface
{
    public function sendMessage(ParticipantInterface $reciever, array $messageBody): void
    {
        $encodedMessage = $this->encodeMessage($messageBody);
        if ($reciever->grouped()) {
            LibGateway::sendToGroup($reciever->identifier(), $encodedMessage);
        } else {
            LibGateway::sendToClient($reciever->identifier(), $encodedMessage);
        }
    }

    public function addToGroup(ParticipantInterface $participant, string $groupName): void
    {
        LibGateway::joinGroup($participant->identifier(), $groupName);
    }

    public function closeParticipant(ParticipantInterface $participant): void
    {
        LibGateway::closeClient($participant->identifier());
    }

    private function encodeMessage(array $message): string
    {
        return \json_encode($message, JSON_THROW_ON_ERROR);
    }
}
