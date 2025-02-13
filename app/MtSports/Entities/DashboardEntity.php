<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class DashboardEntity implements \JsonSerializable
{
    public function __construct(
        private array $entity,
    ) {}

    public function jsonSerialize()
    {
        return $this->entity;
    }
}