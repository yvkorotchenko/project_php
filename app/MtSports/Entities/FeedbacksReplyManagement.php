<?php

declare(strict_types=1);

namespace App\MtSports\Entities;

class FeedbacksReplyManagement implements \JsonSerializable
{
    public function __construct(
        private string $created,
        private int $id,
        private string $message,
        private int $operatorId,
        private string $operatorName,
    ) {}

    public function jsonSerialize()
    {
        return [
            "created"      => $this->created,
            "id"           => $this->id,
            "message"      => $this->message,
            "operatorId"   => $this->operatorId,
            "operatorName" => $this->operatorName,
        ];
    }
}
