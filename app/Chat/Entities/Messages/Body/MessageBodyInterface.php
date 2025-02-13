<?php

declare(strict_types=1);

namespace App\Chat\Entities\Messages\Body;

interface MessageBodyInterface
{
    /**
     * Get message body representation as array
     */
    public function asArray(): array;
}
