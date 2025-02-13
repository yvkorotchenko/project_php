<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\SystemTimingRepository;

class SystemTimingService
{
    public function __construct(
        private SystemTimingRepository $systemTimingRepository,
    ) {}

    public function listPagination(int $page, int $size): \StdClass
    {
        $systemTiming = $this->systemTimingRepository->systemTiming($page, $size);
        $systemTiming->total = $systemTiming->totalItems;

        return $systemTiming;
    }

    public function create(array $systemTiming): void
    {
        $this->systemTimingRepository->createSystemTiming($systemTiming);
    }

    public function update(int $id, array $systemTiming): void
    {
        $this->systemTimingRepository->updateSystemTiming($systemTiming);
    }

    public function delete(string $systemTimingIds): void
    {
        $this->systemTimingRepository->deleteSystemTiming($systemTimingIds);
    }
}