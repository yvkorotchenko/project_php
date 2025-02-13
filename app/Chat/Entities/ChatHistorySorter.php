<?php

declare(strict_types=1);

namespace App\Chat\Entities;

use App\Chat\Models\ChatHistory;

class ChatHistorySorter
{
    private const DIRECTION_ASC = 'asc';
    private const DIRECTION_DESC = 'desc';
    private const DEFAULT_FIELD_NAME = 'chatDate';
    private const DEFAULT_SORT_ORDER = self::DIRECTION_ASC;
    private const ORDER_FIELDS = [
        'chatHistoryId' => 'id',
        'chatDate' => 'chat_date',
        'clientId' => 'client.id',
        'clientNickname' => 'client_name',
        'customerServiceName' => 'customerService.name',
    ];

    private string $fieldName;
    private string $sortOrder;

    public function __construct(string $fieldName = 'chatDate', string $sortOrder = self::DIRECTION_ASC)
    {
        if ('' === $fieldName) {
            $fieldName = self::DEFAULT_FIELD_NAME;
        }

        if ('' === $sortOrder) {
            $sortOrder = self::DEFAULT_SORT_ORDER;
        }

        if ( $sortOrder !== self::DIRECTION_ASC && $sortOrder !== self::DIRECTION_DESC) {
            throw new \LogicException('Invalid sort order');
        }

        if (!\array_key_exists($fieldName, self::ORDER_FIELDS)) {
            throw new \LogicException('Invalid field');
        }

        $this->sortOrder = $sortOrder;
        $this->fieldName = $fieldName;
    }

    public function fieldName(): string
    {
        return self::ORDER_FIELDS[$this->fieldName];
    }

    public function sortOrder(): string
    {
        return $this->sortOrder;
    }
}
