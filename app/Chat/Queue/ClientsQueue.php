<?php

declare(strict_types=1);

namespace App\Chat\Queue;

use App\Chat\Entities\Participants\ParticipantInterface;
use Illuminate\Support\Facades\Redis;

class ClientsQueue implements QueueInterface
{
    private const QUEUE_NAME = 'clients_queue';

    public function add(ParticipantInterface $participant): void
    {
        Redis::command('SADD', [self::QUEUE_NAME, $participant->identifier()]);
    }

    public function remove(ParticipantInterface $participant): void
    {
        Redis::command('SREM', [self::QUEUE_NAME, $participant->identifier()]);
    }

    public function all(): array
    {
        return Redis::command('SMEMBERS', [self::QUEUE_NAME]);
    }

    public function inQueue(ParticipantInterface $client): bool
    {
        return Redis::command('SISMEMBER', [self::QUEUE_NAME, $client->identifier()]) === 1;
    }
}
