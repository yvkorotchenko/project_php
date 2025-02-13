<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class ActivityConfigurationEntities implements \JsonSerializable
{
    private array $activity;

    public function __construct(array $activity)
    {
        foreach ($activity as &$item) {
            if (\is_null($item)) {
                $item = "";
            }
        }

        $this->activity = $activity;
    }

    public function jsonSerialize()
    {
        return $this->activity;
    }
}