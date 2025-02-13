<?php
declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\TestIpWiteListRepository;

class TestIpWhitelistService
{
    public function __construct(
        private TestIpWiteListRepository $testIpWiteList,
    ) {}

    public function setTestIpWhiteList(array $testIpWhiteList): void
    {
        $this->testIpWiteList->create($testIpWhiteList);
    }
}