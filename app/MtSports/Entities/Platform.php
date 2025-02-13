<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class Platform implements \JsonSerializable
{
    public function __construct(
        public int $id,
        private string $identity,
        private string $name,
        private bool $isOpen,
    ) {}

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'identity' => $this->identity,
            'isOpen' => $this->isOpen,
            'name' => $this->name,
        ];
    }
}
