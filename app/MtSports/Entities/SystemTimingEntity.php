<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class SystemTimingEntity implements \JsonSerializable
{
    public function __construct(
        private array $systemTimings
    ) {}

    public function jsonSerialize()
    {
        return $this->systemTimings;
    }
}