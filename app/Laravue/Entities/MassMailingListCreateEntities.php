<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class MassMailingListCreateEntities implements \JsonSerializable
{
    public function __construct(
        public string $content,
        public string $contentLocal,
        public int $goldCoin,
        public string $loginEndTime,
        public string $loginStartTime,
        public int $maxRecharge,
        public int $minRecharge,
        public bool $onTop,
        public int $operatorId,
        public string $operatorName,
        public string $title,
        public string $titleLocal,
        public int $withdrawalStandard,
    ) {}

    public function jsonSerialize()
    {
        return
        [
            "content"            => $this->content,
            "contentLocal"       => $this->contentLocal,
            "goldCoin"           => $this->goldCoin,
            "loginEndTime"       => $this->goldCoin,
            "loginStartTime"     => $this->loginStartTime,
            "maxRecharge"        => $this->maxRecharge,
            "minRecharge"        => $this->minRecharge,
            "onTop"              => $this->onTop,
            "operatorId"         => $this->operatorId,
            "operatorName"       => $this->operatorName,
            "title"              => $this->title,
            "titleLocal"         => $this->titleLocal,
            "withdrawalStandard" => $this->withdrawalStandard,
        ];
    }
}
