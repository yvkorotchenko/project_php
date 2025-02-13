<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class TaskManagement implements \JsonSerializable
{
    public function __construct(
        private int $companyId,
        private string $descriptionEn,
        private string $descriptionSc,
        private int $displayPriority,
        private string $endDateTime,
        private int $id,
        private bool $isOpen,
        private int $item,
        private int $numberOfParticipant,
        private string $picUrlEn,
        private string $picUrlSc,
        private float $requirement,
        private int $reward,
        private string $showEndDateTime,
        private string $startDateTime,
        private string $titleEn,
        private string $titleSc,
        private int $type
    ) {}

    public function jsonSerialize()
    {
        return [
            "companyId"           => $this->companyId,
            "descriptionEn"       => $this->descriptionEn,
            "descriptionSc"       => $this->descriptionSc,
            "displayPriority"     => $this->displayPriority,
            "endDateTime"         => $this->endDateTime,
            "id"                  => $this->id,
            "isOpen"              => $this->isOpen,
            "item"                => $this->item,
            "numberOfParticipant" => $this->numberOfParticipant,
            "picUrlEn"            => $this->picUrlEn,
            "picUrlSc"            => $this->picUrlSc,
            "requirement"         => $this->requirement,
            "reward"              => $this->reward,
            "showEndDateTime"     => $this->showEndDateTime,
            "startDateTime"       => $this->startDateTime,
            "titleEn"             => $this->titleEn,
            "titleSc"             => $this->titleSc,
            "type"                => $this->type
        ];
    }
}
