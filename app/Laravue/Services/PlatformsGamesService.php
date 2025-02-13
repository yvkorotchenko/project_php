<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\PlatformsGamesRepository;

class PlatformsGamesService
{
    public function __construct(
        private PlatformsGamesRepository $platformsGamesRepository,
    ) {}

    public function listPagination($page = 20, $size = 1): \StdClass
    {
        return $this->platformsGamesRepository->game($page, $size);
    }
}
