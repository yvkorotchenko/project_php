<?php

declare(strict_types=1);

namespace App\Chat\Services\MessageHandlers;

use App\Chat\Entities\Messages\IncomingMessage;
use App\Chat\Entities\Messages\MessageCollection;

class PongMessageHandler implements MessageHandlerInterface
{

    public function handle(IncomingMessage $incomingMessage): MessageCollection
    {
        return new MessageCollection();
    }
}
