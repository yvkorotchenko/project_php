<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\StopServiceAnnouncementRepository;

class StopServiceAnnouncementServices
{
    public function __construct(
        private StopServiceAnnouncementRepository $stopServiceAnnouncementRepository,
    ) {}

    public function store(array $data)
    {
        return $this->stopServiceAnnouncementRepository->store($data);
    }
}
