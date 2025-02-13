<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class StopServiceAnnouncementEntities implements \JsonSerializable
{
    public function __construct(
        public string $contentEn,
        public string $contentLocal,
        public string $from,
        public string $to,
        public string $titleEn,
        public string $titleLocal,
    ) {}

    public function jsonSerialize()
    {
        return [
            'contentEn'    => $this->contentEn,
            'contentLocal' => $this->contentLocal,
            'from'         => $this->from,
            'to'           => $this->to,
            'titleEn'      => $this->titleEn,
            'titleLocal'   => $this->titleLocal,
        ];
    }
}
