<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Entities\ImportantNoticeEntity;
use App\MtSports\Repositories\NoticeRepository;

class NoticeService
{
    public function __construct(
        private NoticeRepository $importantNoticeRepository,
    ) {}

    public function listPagination(int $page = 1, int $size = 20): \StdClass
    {
        $notices = $this->importantNoticeRepository->importantNotice($page, $size);
        $notices->total = $notices->totalItems;

        return $notices;
    }

    public function create(array $noticeData): void
    {
        $this->importantNoticeRepository->create(
            new ImportantNoticeEntity(
                null,
                $noticeData['titleEn'],
                $noticeData['titleLocal'],
                $noticeData['contentEn'],
                $noticeData['contentLocal'],
                $noticeData['state']
        ));
    }

    public function update(array $noticeData): void
    {
        $this->importantNoticeRepository->update(
            new ImportantNoticeEntity(
                $noticeData['id'],
                $noticeData['titleEn'],
                $noticeData['titleLocal'],
                $noticeData['contentEn'],
                $noticeData['contentLocal'],
                $noticeData['state']
            )
        );
    }

    public function delete(array $ids): void
    {
        if (count($ids) === 0) {
            return;
        }
        $this->importantNoticeRepository->delete($ids);
    }
}