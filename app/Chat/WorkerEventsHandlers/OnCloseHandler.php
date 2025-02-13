<?php

declare(strict_types=1);

namespace App\Chat\WorkerEventsHandlers;

use App\Chat\Entities\Messages\MessageInterface;
use App\Chat\Entities\Participants\Participant;
use App\Chat\Services\CloseConnectionHandlerDispatcher;
use App\Chat\WorkerEventsHandlers\Interfaces\OnCloseHandlerInterface;

class OnCloseHandler implements OnCloseHandlerInterface
{
    private CloseConnectionHandlerDispatcher $closeConnectionHandlerDispatcher;

    public function __construct(CloseConnectionHandlerDispatcher $closeConnectionHandlerDispatcher)
    {
        $this->closeConnectionHandlerDispatcher = $closeConnectionHandlerDispatcher;
    }

    public function __invoke(string $participantId)
    {
        $participant = new Participant($participantId);
        $messages = $this
            ->closeConnectionHandlerDispatcher
            ->handler($participant)
            ->handle($participant);

        /** @var MessageInterface $message */
        foreach ($messages as $message) {
            $message->send();
        }
    }
}
