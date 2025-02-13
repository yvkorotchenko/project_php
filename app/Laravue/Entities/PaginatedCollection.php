<?php

namespace App\Laravue\Entities;

class PaginatedCollection
{
    public function __construct(
        private array $items = [],
        private int $total = 0,
        private int $page = 1
    ){}

    public function items(): array
    {
        return $this->items;
    }

    public function total(): int
    {
        return $this->total;
    }

    public function page(): int
    {
        return $this->page;
    }
}
