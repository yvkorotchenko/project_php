<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class TestIpWiteListEntitie implements \JsonSerializable
{
    public function __construct(
        private array $testIpWiteList,
    ) {}

    public function jsonSerialize(): array
    {
        return $this->testIpWiteList;
    }
}