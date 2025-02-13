<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\AllPlayersQueryRepositories;

class AllPlayersQueryServices
{
    public function __construct(
        private AllPlayersQueryRepositories $allPlayersQuery,
    ) {}

    public function list(array $query)
    {
        return $this->allPlayersQuery->list($query);
    }

    public function channel()
    {
        return $this->allPlayersQuery->channel();
    }

    public function vipLevel()
    {
        return $this->allPlayersQuery->vipLevel();
    }

}