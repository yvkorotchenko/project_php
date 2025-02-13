<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\AccountSuspensionRepositories;

class AccountSuspensionServices
{
    public function __construct(
        private AccountSuspensionRepositories $accountSuspension,
    ) {}

    public function sendPlayerBanned(int $id)
    {
        return $this->accountSuspension->sendPlayerBanned($id);
    }

    public function sendPlayerUnBanned(int $id)
    {
        return $this->accountSuspension->sendPlayerUnBanned($id);
    }
}