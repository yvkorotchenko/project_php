<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\LeaguesRepository;

class LeaguesService
{
    public function __construct(
        private LeaguesRepository $leaguesRepository,
    ) {}

    public function listPagination(int $page = 1, int $size = 20): \StdClass
    {
        $leagues = $this->leaguesRepository->leagues($page, $size);
        $leagues->total = $leagues->totalItems;
        return $leagues;
    }

    public function update(array $leagues): void
    {
        $this->leaguesRepository->updateLeagues($leagues);
    }

    public function previousLeagueSelection(): \StdClass
    {
        return $this->leaguesRepository->previousLeagueSelection();
    }
}