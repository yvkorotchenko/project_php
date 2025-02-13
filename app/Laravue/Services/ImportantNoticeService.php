<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\ImportantNoticeRepository;

class ImportantNoticeService
{
    public function __construct(
        private ImportantNoticeRepository $importantNotice,
    ) {}

    public function listPagination(int $page = 1, int $size = 20): \StdClass {
        $notices = $this->importantNotice->importantNotice($page, $size);
        $notices->total = $notices->totalItems;

        return $notices;
    }

    public function create(array $notices):void
    {
        $this->importantNotice->create($notices);
    }

    public function update(int $id, array $notices):void
    {
        $this->importantNotice->update($id, $notices);
    }

    public function delete(array $noticesIds):void
    {
        $this->importantNotice->delete($noticesIds);
    }
}
