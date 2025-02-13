<?php
declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\ForbiddenIpLoginRepository;

class ForbiddenIpLoginService
{
    public function __construct(
        private ForbiddenIpLoginRepository $forbiddenIpLogin,
    ) {}

    public function create(array $forbiddenIpLogin): void
    {
        $this->forbiddenIpLogin->create($forbiddenIpLogin);
    }
}