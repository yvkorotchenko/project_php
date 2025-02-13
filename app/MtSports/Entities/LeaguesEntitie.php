<?php


namespace App\MtSports\Entities;

class LeaguesEntitie implements \JsonSerializable
{
    public function __construct(
        private array $leagues,
    ) {}

    public function jsonSerialize()
    {
        return $this->leagues;
    }
}