<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class PlatformGameCategory implements \JsonSerializable
{
    public function __construct(
        private int $id,
        private string $name,
        private int $sequence,
    ) {}

    public function id(): int
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'sequence' => $this->sequence,
        ];
    }
}
