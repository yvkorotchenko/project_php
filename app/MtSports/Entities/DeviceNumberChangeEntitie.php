<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class DeviceNumberChangeEntitie implements \JsonSerializable
{
    public function __construct(
        private array $deviceNumberChange
    ) {}

    public function jsonSerialize()
    {
        return $this->deviceNumberChange;
    }
}