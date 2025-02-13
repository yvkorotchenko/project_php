<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class MemberInfoEntities implements \JsonSerializable
{
    public function __construct(
        public int    $uid,
        public string $nickname,
        public string $phone,
        public string $email,
        public int    $effectiveBet,
        public int    $withdrawStandard,
        public string $countryCode,
    ) {
    }

    public function jsonSerialize()
    {
        return [
            'uid'              => $this->uid,
            'nickname'         => $this->nickname,
            'phone'            => $this->phone,
            'email'            => $this->email,
            'effectiveBet'     => $this->effectiveBet,
            'withdrawStandard' => $this->withdrawStandard,
            'countryCode'      => $this->countryCode,
        ];
    }
}
