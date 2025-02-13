<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\RewardsManagementRepository;
use App\Laravue\Traits\UploadImage;

class RewardsManagementService
{
    public function __construct(
        private RewardsManagementRepository $rewardsManagement,
        private StaticStorageService $staticStorageService,
    ) {}

    use UploadImage;

    public function listPagination(int $page = 1, int $size = 20): \StdClass
    {
        $rewards = $this->rewardsManagement->rewards($page, $size);
        $rewards->total = $rewards->totalItems;

        return $rewards;
    }

    public function create(array $rewards): void
    {
        $this->rewardsManagement->create($rewards);
    }

    public function update(int $id, array $rewards): void
    {
        $this->rewardsManagement->update($id, $rewards);
    }

    public function delete(string $rewardsIds, array $links): void
    {
        $urls = [];

        $this->rewardsManagement->delete($rewardsIds);

        foreach ($links as $link) {
            if(!empty($link['en'])){
                array_push($urls, $link['en']);
            }
            if(!empty($link['zh'])){
                array_push($urls, $link['zh']);
            }
        }
        if(!empty($urls)){
            $this->staticStorageService->deleteImage($urls);
        }

    }
}
