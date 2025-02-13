<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class ForbiddenIpLoginEntitie implements \JsonSerializable
{
    public function __construct(
        private $forbiddenIpLogin,
    ) {}

    public function jsonSerialize()
    {
        return $this->forbiddenIpLogin;
    }
}