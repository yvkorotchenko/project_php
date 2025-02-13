<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\ClientIPManagementRepository;

class ClientIPManagementServices
{
    public function __construct(
        private ClientIPManagementRepository $clientIPManagementRepository,
    ) {}

    public function list(int $page = 1, int $size = 20)
    {
        $result = $this->clientIPManagementRepository->list($page, $size);
        $result->total = $result->data->totalItems;

        return $result;
    }

    public function update(int $id, bool $state)
    {
        return $this->clientIPManagementRepository->update($id, $state);
    }

}