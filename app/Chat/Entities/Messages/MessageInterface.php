<?php

declare(strict_types=1);

namespace App\Chat\Entities\Messages;

use App\Chat\Entities\Messages\Body\MessageBodyInterface;

interface MessageInterface
{
    /** Send the message to recievers */
    public function send(): void;

    /** Returns body of the message */
    public function body(): MessageBodyInterface;
}
