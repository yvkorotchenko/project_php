<?php

declare(strict_types=1);

namespace App\Chat\Repositories;

use App\Chat\Entities\Participants\ParticipantInterface;
use App\Chat\Models\QuestionType;
use Illuminate\Support\Facades\Redis;

class ClientChatInfoRepository
{
    private const CLIENT_STATUSES_HASH = 'client_statuses';
    private const CLIENT_QUESTION_TYPE = 'client_question_types';
    private const STATUS_WAITING = 'Waiting';
    private const STATUS_SERVING = 'Serving';

    public function status(ParticipantInterface $client): string
    {
        if (!Redis::command('HEXISTS', [self::CLIENT_STATUSES_HASH, $client->identifier()])) {
            throw new \LogicException('Try to get status for non-registered client: ' . $client->identifier());
        }

        return (string)Redis::command('HGET', [self::CLIENT_STATUSES_HASH, $client->identifier()]);
    }

    public function updateStatus(ParticipantInterface $client, string $status): void
    {
        if ($status !== self::STATUS_WAITING && $status !== self::STATUS_SERVING) {
            throw new \LogicException('Try to assign undefined status to client: ' . $status);
        }
        Redis::command('HSET', [self::CLIENT_STATUSES_HASH, $client->identifier(), $status]);
    }

    public function remove(ParticipantInterface $client): void
    {
        if (Redis::command('HEXISTS', [self::CLIENT_STATUSES_HASH, $client->identifier()])) {
            Redis::command('HDEL', [self::CLIENT_STATUSES_HASH, $client->identifier()]);
        }
        if (Redis::command('HEXISTS', [self::CLIENT_QUESTION_TYPE, $client->identifier()])) {
            Redis::command('HDEL', [self::CLIENT_QUESTION_TYPE, $client->identifier()]);
        }
    }

    public function updateQuestionType(ParticipantInterface $client, QuestionType $questionType): void
    {
        Redis::command('HSET', [self::CLIENT_QUESTION_TYPE, $client->identifier(), $questionType->id]);
    }

    public function questionType(ParticipantInterface $client): ?QuestionType
    {
        if (!Redis::command('HEXISTS', [self::CLIENT_QUESTION_TYPE, $client->identifier()])) {
            throw new \LogicException('Try to get question-type for non-registered client: ' . $client->identifier());
        }
        $questionTypeId = Redis::command('HGET', [self::CLIENT_QUESTION_TYPE, $client->identifier()]);

        return QuestionType::find($questionTypeId);
    }
}
