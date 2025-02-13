<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\MassMailingListRepository;

class MassMailingListServices
{
    public function __construct(
        private MassMailingListRepository $massmailingListRepository,
    ) {}

    public function create(array $data)
    {
        return $this->massmailingListRepository->create($data);
    }
}