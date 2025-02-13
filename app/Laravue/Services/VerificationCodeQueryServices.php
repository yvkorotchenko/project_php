<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\VerificationCodeQueryRepositories;

class VerificationCodeQueryServices
{
    public function __construct(
        private VerificationCodeQueryRepositories $verificationCodeQuery,
    ) {}

    public function getVerificationCode(array $query)
    {
        return $this->verificationCodeQuery->getVerificationCode($query);
    }
}