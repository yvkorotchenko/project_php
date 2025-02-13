<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class SevenDaysWelfareDeleteIdsEntities implements \JsonSerializable
{
    private array $sevenDaysWelfareIds;

    /**
     * @param string $sevenDaysWelfareIds
     */
    public function __construct(
        string $sevenDaysWelfareIds
    )
    {
        $this->sevenDaysWelfareIds = explode(',', $sevenDaysWelfareIds);
    }

    public function jsonSerialize()
    {
        return $this->sevenDaysWelfareIds;
    }
}