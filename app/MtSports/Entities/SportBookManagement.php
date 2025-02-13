<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class SportBookManagement implements \JsonSerializable
{
    private array $sportBookManagement;

    public function __construct(array $sportBookManagement)
    {
        $sportBookManagement['sportBookManagement']['reward'] = \json_encode(
            $sportBookManagement['sportBookManagement']['reward'],
            JSON_THROW_ON_ERROR,
            512,
        );

        $this->sportBookManagement = $sportBookManagement;
    }

    public function jsonSerialize()
    {
        return $this->sportBookManagement;
    }
}