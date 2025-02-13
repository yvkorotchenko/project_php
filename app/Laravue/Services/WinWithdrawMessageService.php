<?php

declare(strict_types=1);

namespace App\Laravue\Services;


use App\MtSports\Repositories\WinWithdrawMessageRepository;

class WinWithdrawMessageService
{
    public function __construct(
        private WinWithdrawMessageRepository $winWithdrawMessageRepository,
    ) {}

    public function listPagination(int $page = 1, int $size = 20): \StdClass
    {
        $winWithdrawMessage = $this->winWithdrawMessageRepository->winWithdrawMessage($size, $page);
        $winWithdrawMessage->total = $winWithdrawMessage->totalPages;

        return $winWithdrawMessage;
    }

    public function create(array $withdrawMessage): void
    {
        $this->winWithdrawMessageRepository->createWinWithdrawMessage($withdrawMessage);
    }

    public function delete(string $withdrawMessageId): void
    {
        $this->winWithdrawMessageRepository->deleteWinWithdrawMessage($withdrawMessageId);
    }
}