<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\NewbieMailRepository;

class NewbieMailServices
{
    public function __construct(
        private NewbieMailRepository $newbieMailRepository,
    ) {}

    public function store(array $data)
    {
        return $this->newbieMailRepository->store($data);
    }
}