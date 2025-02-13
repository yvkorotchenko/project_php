<?php


namespace App\Chat\Entities;


class CustomerServices implements \JsonSerializable
{
    public function __construct(
        public int      $id,
        public string   $name,
    ) {}

    public function jsonSerialize()
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
        ];
    }
}