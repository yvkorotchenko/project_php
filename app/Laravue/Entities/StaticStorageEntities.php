<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class StaticStorageEntities implements \JsonSerializable
{
    private array $items;

    public function __construct(array $items)
    {
        foreach ($items as &$item) {
            if (\is_null($item)) {
                $item = "";
            }
        }

        $this->items = $items;
    }

    public function jsonSerialize()
    {
        return $this->items;
    }
}