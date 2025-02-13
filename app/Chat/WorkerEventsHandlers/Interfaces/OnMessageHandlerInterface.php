<?php

declare(strict_types=1);

namespace App\Chat\WorkerEventsHandlers\Interfaces;

interface OnMessageHandlerInterface
{
    public function __invoke(string $participantId, string $message);
}
