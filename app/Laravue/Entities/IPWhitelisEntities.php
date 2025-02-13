<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class IPWhitelisEntities implements \JsonSerializable
{
    public function __construct(
        public string       $ipAddress,
        public int          $operatorId,
        public string       $remark,
    ) {}

    public function jsonSerialize()
    {
        return [
            'ipAddress'  => $this->ipAddress,
            'operatorId' => $this->operatorId,
            'remark'     => $this->remark,
        ];
    }
}