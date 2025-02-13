<?php

declare(strict_types=1);

namespace App\Chat\Entities;

class ChatHistoryFilter
{
    private const DEFAULT_LIMIT = 1000;

    private ?\DateTimeImmutable $from = null;
    private ?\DateTimeImmutable $to = null;
    private int $customerServiceId;
    private int $limit = self::DEFAULT_LIMIT;

    public function __construct(
        string $from = '',
        string $to = '',
        int $customerServiceId = 0,
        int $limit = self::DEFAULT_LIMIT
    ) {
        if (0 > $customerServiceId) {
            throw new \LogicException('Invalid customer id');
        }
        if (0 >= $limit) {
            $limit = self::DEFAULT_LIMIT;
        }
        if ('' === $from && '' !== $to) {
            throw new \LogicException('The lower date is not defined, but upper limit exists');
        }
        if ('' !== $from && '' === $to) {
            $to = date('Y-m-d H:i:s');
        }
        if ('' !== $from && '' !== $to) {
            $this->from = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $from);
            $this->to = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $to);
        }

        $this->customerServiceId = $customerServiceId;
        $this->limit = $limit;
    }

    public function datesExists(): bool
    {
        return null !== $this->from && null !== $this->to;
    }

    public function from(): ?\DateTimeImmutable
    {
        return $this->from;
    }

    public function to(): ?\DateTimeImmutable
    {
        return $this->to;
    }

    public function customerServiceIdExists(): bool
    {
        return $this->customerServiceId !== 0;
    }

    public function customerServiceId(): int
    {
        return $this->customerServiceId;
    }

    public function limit(): int
    {
        return $this->limit;
    }
}
