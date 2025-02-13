<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\BannerRepository;
use App\Laravue\Services\StaticStorageService;
use App\Laravue\Traits\UploadImage;

class BannerService
{
    public function __construct(
        private BannerRepository $bannerRepository,
        private StaticStorageService $staticStorageService
    ) {}
    // upload image
    use UploadImage;

    public function listPagination(int $page = 1, int $size = 20): \StdClass
    {
        $banner = $this->bannerRepository->banner($page, $size);
        $banner->total = $banner->totalItems;

        return $banner;
    }

    public function create(array $banner): void
    {
        $this->bannerRepository->create($banner);
    }

    public function update(int $id, array $banner): void
    {
        $this->bannerRepository->update($id, $banner);
    }

    public function delete(string $bannersIds, array $links): void
    {
        $urls = [];

        $this->bannerRepository->delete($bannersIds);

        if ( !empty($links['en']) ) {
            $temp = $links;
            $links = [];
            $links[] = $temp;
        }

        foreach ($links as $link) {
            array_push($urls, $link['en'], $link['zh']);
        }

        $this->staticStorageService->deleteImages($urls);
    }
}
