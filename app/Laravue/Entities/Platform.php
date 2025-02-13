<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

use \JsonSerializable;

class Platform implements JsonSerializable
{
    public function __construct(
        public int $id,
        public string $name,
        public bool $visible,
        public string $parentName
    ){}

    public function id(): int
    {
        return $this->id;
    }

    public function array(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'visible' => $this->visible,
            'parentName' => $this->parentName,
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->array();
    }
}
