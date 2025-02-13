<?php

declare(strict_types=1);

namespace App\MtSports\Entities;


class Banner implements \JsonSerializable
{
    public function __construct(
        private array $banner
    ) {}

    public function jsonSerialize()
    {
        return $this->banner;
    }
}