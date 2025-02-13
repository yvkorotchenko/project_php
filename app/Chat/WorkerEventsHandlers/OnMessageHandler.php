<?php

declare(strict_types=1);

namespace App\Chat\WorkerEventsHandlers;

use App\Chat\Entities\Messages\MessageInterface;
use App\Chat\Factories\IncomingMessageFactory;
use App\Chat\Services\MessageHandlerDispatcher;
use App\Chat\WorkerEventsHandlers\Interfaces\OnMessageHandlerInterface;

class OnMessageHandler implements OnMessageHandlerInterface
{
    private MessageHandlerDispatcher $messageHandlerDispatcher;
    private IncomingMessageFactory $incomingMessageFactory;

    public function __construct(
        MessageHandlerDispatcher $messageHandlerDispatcher,
        IncomingMessageFactory $incomingMessageFactory
    ) {
        $this->messageHandlerDispatcher = $messageHandlerDispatcher;
        $this->incomingMessageFactory = $incomingMessageFactory;
    }

    public function __invoke(string $participantId, string $message)
    {
        $incomingMessage = $this->incomingMessageFactory->createByRawData($participantId, $message);
        try {
            $messages = $this->messageHandlerDispatcher
                ->hander($incomingMessage)
                ->handle($incomingMessage);

            /** @var MessageInterface $message */
            foreach ($messages as $message) {
                $message->send();
            }
        } catch (\Throwable $e) {
            dump($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }
}
