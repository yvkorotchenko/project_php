<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class SevenDaysWelfareEntities implements \JsonSerializable
{
    public function __construct(
        private array $sevenDaysWelfare,
    ) {
        if (is_array($this->sevenDaysWelfare['buttons']) && empty($this->sevenDaysWelfare['buttons'])) {
            $this->sevenDaysWelfare['buttons'] = new \StdClass();
        }
    }

    public function jsonSerialize()
    {
        return $this->sevenDaysWelfare;
    }
}