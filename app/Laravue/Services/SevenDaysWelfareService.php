<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\SevenDaysWelfareRepository;
use App\Laravue\Traits\UploadImage;

class SevenDaysWelfareService
{
    public function __construct(
        private SevenDaysWelfareRepository $sevenDaysWelfare,
        private StaticStorageService $staticStorageService,
    ) {}

    use UploadImage;

    public function listPagination(int $page = 1, int $size = 20): \StdClass
    {
        $sevenDaysWelfare = $this->sevenDaysWelfare->sevenDaysWelfare($page, $size);
        $sevenDaysWelfare->total = $sevenDaysWelfare->totalItems;

        return $sevenDaysWelfare;
    }

    public function create(array $sevenDaysWelfare): void
    {
        $this->sevenDaysWelfare->create($sevenDaysWelfare);
    }

    public function update(int $id, array $sevenDaysWelfare)
    {
        $this->sevenDaysWelfare->update($id, $sevenDaysWelfare);
    }

    public function delete(string $sevenDaysWelfareIds, array $links): void
    {
        if ($sevenDaysWelfareIds !== '0') {
            $this->sevenDaysWelfare->delete($sevenDaysWelfareIds);
        }

        if (count($links)  > 0) {
            $this->staticStorageService->deleteImage($links);
        }
    }
}
