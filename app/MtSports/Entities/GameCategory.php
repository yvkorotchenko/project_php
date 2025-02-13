<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class GameCategory implements \JsonSerializable
{
    public function __construct(
        public int $id,
        public string $identity,
        public string $name,
        public string $nameLocal,
        public bool $isOpen,
        public int $seq,
    ) {}

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'identity' => $this->identity,
            'name' => $this->name,
            'nameLocal' => $this->nameLocal,
            'isOpen' => $this->isOpen,
            'seq' => $this->seq,
        ];
    }
}