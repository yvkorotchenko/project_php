<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\ShareAddressRepositories;

class ShareAddressServices
{
    public function __construct(
        private ShareAddressRepositories $shareAddress,
    ) {}

    public function get(int $id)
    {
        return $this->shareAddress->get($id);
    }

    public function update(array $query)
    {
        return $this->shareAddress->update($query);
    }
}