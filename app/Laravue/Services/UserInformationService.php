<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\UserInformationRepository;

class UserInformationService
{

    public function __construct(
        private UserInformationRepository $userInformationRepositories
    ) {}

    public function userCountRegistration(): array
    {
        return $this->userInformationRepositories->userRegistration();
    }

    public function userCountLogin(): array
    {
        return $this->userInformationRepositories->userLogin();
    }

    public function newUsersCount(): array
    {
      return $this->userInformationRepositories->newUserCount();
    }
}