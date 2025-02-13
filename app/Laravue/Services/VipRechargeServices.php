<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\VipRechargeRepositories;

class VipRechargeServices
{
    public function __construct(
        private VipRechargeRepositories $vipRecharge,
    ) {}

    public function createRecharge(array $query)
    {
        return $this->vipRecharge->createRecharge($query);
    }

    public function listRechargeHistory(array $query)
    {
        $vipRechargeList = $this->vipRecharge->listRechargeHistory($query);
        $vipRechargeList->total = $vipRechargeList->data->totalItems;

        return $vipRechargeList;
    }

    public function listRechargeStatuses()
    {
        return $this->vipRecharge->listRechargeStatuses();
    }
}