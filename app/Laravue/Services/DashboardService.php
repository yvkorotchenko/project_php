<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\DashboardRepository;

class DashboardService
{
    public function __construct(
        private DashboardRepository $dashboard,
    ) {}

    public function totalStatistics(): array
    {
        return $this->dashboard->totalStatistics();
    }

    public function betAmountPlatform(): array
    {
        return $this->dashboard->betAmountPlatform();
    }

    public function rechargeWithdrawal(): array
    {
        return $this->dashboard->rechargeWithdrawal();
    }

    public function platformProfitLossPlayerBetting(int $id): array
    {
        return $this->dashboard->platformProfitLossPlayerBetting($id);
    }

    public function platformLists(): array
    {
        return $this->dashboard->platformLists();
    }

    public function betAmountPlatformLists(): array
    {
        return $this->dashboard->betAmountPlatformLists();
    }
}