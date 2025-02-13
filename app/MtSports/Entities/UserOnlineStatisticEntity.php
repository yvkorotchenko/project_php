<?php

namespace App\MtSports\Entities;

class UserOnlineStatisticEntity implements \JsonSerializable
{
    private array $userOnlineInGameByDateData;

    public function __construct(array $userOnlineInGameByDateData) {
        $this->userOnlineInGameByDateData = $userOnlineInGameByDateData;
    }

    public function jsonSerialize()
    {
        return [
            'userOnlineInGameByDateData' => $this->userOnlineInGameByDateData,
        ];
    }
}