<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\UserLoginLogRepository;

class UserLoginLogServices
{
    public function __construct(
        private UserLoginLogRepository $userLoginLog,
    ) {}

    public function listPagination(int $page, int $size, int $uid): \StdClass
    {
        return $this->userLoginLog->listPagination($page, $size, $uid);
    }
}