<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class NewbieMailEntities implements \JsonSerializable
{
    public function __construct(
        public string $contentEn,
        public string $contentLocal,
        public int    $state,
        public string $titleEn,
        public string $titleLocal,
        public int $operatorId,
        public string $operatorName,
    ) {}

    public function jsonSerialize()
    {
        return [
            'contentEn'    => $this->contentEn,
            'contentLocal' => $this->contentLocal,
            'state'        => $this->state,
            'titleEn'      => $this->titleEn,
            'titleLocal'   => $this->titleLocal,
            'operatorId'   => $this->operatorId,
            'operatorName' => $this->operatorName,
        ];
    }
}