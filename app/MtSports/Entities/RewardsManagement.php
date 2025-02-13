<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class RewardsManagement implements \JsonSerializable
{
    /**
     * @param array $rewards
     */
    public function __construct(
        private array $rewards
    ) {}

    public function jsonSerialize()
    {
        return $this->rewards;
    }
}