<?php

declare(strict_types=1);

namespace App\Chat\Services\MessageHandlers;

use App\Chat\Entities\Messages\IncomingMessage;
use App\Chat\Entities\Messages\MessageCollection;

interface MessageHandlerInterface
{
    public function handle(IncomingMessage $incomingMessage): MessageCollection;
}
