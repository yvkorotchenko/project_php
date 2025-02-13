<?php

declare(strict_types=1);

namespace App\Chat\WorkerEventsHandlers\Interfaces;

interface OnCloseHandlerInterface
{
    public function __invoke(string $participantId);
}
