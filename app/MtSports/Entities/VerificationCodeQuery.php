<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class VerificationCodeQuery implements \JsonSerializable
{
    public function __construct(
        private string $phone,
        private string $countryCode,
        private string $language
    ) {}

    public function jsonSerialize()
    {
        return [
            'phone'       => $this->phone,
            'countryCode' => $this->countryCode,
            'language'    => $this->language,
        ];
    }
}
