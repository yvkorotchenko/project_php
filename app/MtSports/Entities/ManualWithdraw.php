<?php

namespace App\MtSports\Entities;

use Exception;

class ManualWithdraw implements \JsonSerializable
{
    public function __construct(
        private int $uid,
        private int $merchantId,
        private float $amount,
        private string $receiveAddress,
        private int $operatorId,
        private ?string $notice
    )
    {}

    public function jsonSerialize()
    {
        return [
            'uid' => $this->uid,
            'merchantId' => $this->merchantId,
            'amount' => $this->amount,
            'receiveAddress' => $this->receiveAddress,
            'operatorId' => $this->operatorId,
            'notice' => $this->notice,
        ];
    }
}