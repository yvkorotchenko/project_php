<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\MailingListRepository;

class MailingListServices
{
    public function __construct(
        private MailingListRepository $mailingListRepository,
    ) {}

    public function list(array $data)
    {
        return $this->mailingListRepository->list($data);
    }

    public function destroy(array $data)
    {
        return $this->mailingListRepository->destroy($data);
    }

    public function create(array $data)
    {
        return $this->mailingListRepository->create($data);
    }
}