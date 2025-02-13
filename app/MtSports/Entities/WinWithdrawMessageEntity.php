<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class WinWithdrawMessageEntity implements \JsonSerializable
{
    public function __construct(
        private array $withdrawMessage
    ) {}

    public function jsonSerialize()
    {
        return $this->withdrawMessage;
    }
}