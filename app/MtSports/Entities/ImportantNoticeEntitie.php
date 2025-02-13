<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class ImportantNoticeEntitie implements \JsonSerializable
{
    public function __construct(
        private $notice
    ) {}

    public function jsonSerialize()
    {
        return $this->notice;
    }
}