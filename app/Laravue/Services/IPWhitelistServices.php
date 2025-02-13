<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\IPWhitelisRepository;

class IPWhitelistServices
{
    public function __construct(
        private IPWhitelisRepository $iPWhitelisRepository,
    ) {}

    public function listPagination(int $page, int $size): \StdClass
    {
        return $this->iPWhitelisRepository->listPagination($page, $size);
    }

    public function store(array $data): void
    {
        $this->iPWhitelisRepository->store($data);
    }

    public function destroy($id): void
    {
        $this->iPWhitelisRepository->destroy($id);
    }
}