<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class PlatformCategories implements \JsonSerializable
{
    private array $platformCatgories;

    public function __construct(array $platformCategories)
    {
        foreach ($platformCategories as $platformCategory) {
            if (
                !\array_key_exists('id', $platformCategory)
                || !\is_int($platformCategory['id'])
                || !\array_key_exists('seq', $platformCategory)
                || !\is_int($platformCategory['seq'])
            ) {
                throw new \LogicException('Invalid input data');
            }
        }

        $this->platformCatgories = $platformCategories;
    }

    public function jsonSerialize()
    {
        return [
            'categories' => $this->platformCatgories
        ];
    }
}
