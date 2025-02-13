<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class GameCategory implements \JsonSerializable
{
    public function __construct(
       public int $id,
       public string $identity,
       public string $name,
       public string $nameLocal,
       public int $sequence,
       public bool $visible,
    ) {}

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nameLocal' => $this->nameLocal,
            'identity' => $this->identity,
            'sequence' => $this->sequence,
            'visible' => $this->visible,
        ];
    }
}
