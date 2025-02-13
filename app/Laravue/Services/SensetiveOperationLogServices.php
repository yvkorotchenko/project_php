<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\SensetiveOperationLogRepositories;

class SensetiveOperationLogServices
{
    public function __construct(
        private SensetiveOperationLogRepositories $sensetiveOperationLogRepositories,
    ) {}

    public function list(int $page, int $size, int $uid)
    {
        return $this->sensetiveOperationLogRepositories->list($page, $size, $uid);
    }
}