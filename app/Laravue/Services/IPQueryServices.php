<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\IPQueryRepository;

class IPQueryServices
{
    public function __construct(
        private IPQueryRepository $iPQueryRepository,
    ) {}

    public function listPagination(int $page, int $size, string $ip): \StdClass
    {
        return $this->iPQueryRepository->listPagination($page, $size, $ip);
    }
}