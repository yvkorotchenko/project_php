<?php

declare(strict_types=1);

namespace App\Chat\Services\CloseConnectionHandlers;

use App\Chat\Entities\Messages\MessageCollection;
use App\Chat\Entities\Participants\ParticipantInterface;

interface CloseConnectionHandlerInterface
{
    public function handle(ParticipantInterface $participant): MessageCollection;
}
