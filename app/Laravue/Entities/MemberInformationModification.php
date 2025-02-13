<?php

declare(strict_types=1);

namespace App\Laravue\Entities;

class MemberInformationModification implements \JsonSerializable
{
    private array $member;

    public function __construct(array $member) {

        foreach ($member as &$item) {
            if (\is_null($item)) {
                $item = "";
            }
        }

        $this->member = $member;
    }

    public function jsonSerialize()
    {
        return $this->member;
    }
}