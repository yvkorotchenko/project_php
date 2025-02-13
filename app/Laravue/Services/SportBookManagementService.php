<?php

declare(strict_types=1);

namespace App\Laravue\Services;


use App\MtSports\Repositories\SportBookManagementRepository;

class SportBookManagementService
{
    public function __construct(
        private SportBookManagementRepository $leagues,
    ) {}

    public function getAllLeagues(): \StdClass
    {
        $leagues = $this->leagues->getAllLeagues();
//        $leagues->total = $leagues->totalItems;
        return $leagues;
    }

    public function setAllLeagues(array $leagues)
    {
        $leagues = $this->leagues->setAllLeagues($leagues);
        return $leagues;
    }
}
