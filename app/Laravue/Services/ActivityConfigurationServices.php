<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\ActivityConfigurationRepository;

class ActivityConfigurationServices
{
    public function __construct(
        private ActivityConfigurationRepository $activityConfiguration,
    ) {}

    public function listPagination(): \StdClass
    {

        return $this->activityConfiguration->activityConfiguration();
    }

    public function update(array $activitys)
    {
        return $this->activityConfiguration->updateActivityConfiguration($activitys);
    }
}