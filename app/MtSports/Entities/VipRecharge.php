<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class VipRecharge implements \JsonSerializable
{
    public function __construct(
        private int $operatorId,
        private string $amount,
        private string $uid
    ) {}

    public function jsonSerialize()
    {
        return [
            'operatorId' => $this->operatorId,
            'amount'     => $this->amount,
            'uid'        => $this->uid,
        ];
    }
}
