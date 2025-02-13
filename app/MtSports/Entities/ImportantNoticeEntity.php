<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class ImportantNoticeEntity implements \JsonSerializable
{
    public function __construct(
        private ?int   $id,
        private string $titleEn,
        private string $titleLocal,
        private string $contentEn,
        private string $contentLocal,
        private bool   $state
    ) {}

    public function getId(): int
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'titleEn' => $this->titleEn,
            'titleLocal' => $this->titleLocal,
            'contentEn' => $this->contentEn,
            'contentLocal' => $this->contentLocal,
            'state' => $this->state,
        ];
    }
}