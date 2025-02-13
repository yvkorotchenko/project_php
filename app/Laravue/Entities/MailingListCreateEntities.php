<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class MailingListCreateEntities implements \JsonSerializable
{
    public function __construct(
        public string $content,
        public string $contentLocal,
        public int $goldCoin,
        public int $increaseCoef,
        public int|null $increaseValue,
        public bool $increaseWithdrawStandart,
        public bool $onTop,
        public int $operatorId,
        public string $operatorName,
        public string $title,
        public string $titleLocal,
        public array $uids,
    ) {}

    public function jsonSerialize()
    {
        return 
        [
            'content'                  => $this->content,
            'contentLocal'             => $this->contentLocal,
            'goldCoin'                 => $this->goldCoin,
            'increaseCoef'             => $this->increaseCoef,
            'increaseValue'            => $this->increaseValue,
            'increaseWithdrawStandart' => $this->increaseWithdrawStandart,
            'onTop'                    => $this->onTop,
            'operatorId'               => $this->operatorId,
            'operatorName'             => $this->operatorName,
            'title'                    => $this->title,
            'titleLocal'               => $this->titleLocal,
            'uids'                     => $this->uids,
        ];
    }
}
