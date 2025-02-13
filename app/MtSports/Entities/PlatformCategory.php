<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class PlatformCategory implements \JsonSerializable
{
    public function __construct(
        private int $categoryId,
        private int $seq
    ) {}

    public function jsonSerialize()
    {
        return [
            'id' => $this->categoryId,
            'seq' => $this->seq,
        ];
    }
}
