<?php

declare(strict_types=1);

namespace App\Chat\Repositories;

use App\Chat\Entities\ChatHistoryFilter;
use App\Chat\Entities\ChatHistorySorter;
use App\Chat\Entities\Participants\ParticipantInterface;
use App\Chat\Models\ChatHistory;
use App\MtSports\Models\Client;
use \Illuminate\Database\Eloquent\Collection;
use \Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use function PHPUnit\Framework\once;

class ChatHistoryRepository
{
    private const NOT_COMPLETED_CHAT_HISTORIES_MAP = 'not_completed_chat_histories';

    public function chatHistoryDetailed(int $id): ?ChatHistory
    {
        return $this->withDetailed(ChatHistory::query())->find($id);
    }

    public function chatHistoryDetailedAll(): Collection
    {
        return $this->withDetailed(ChatHistory::query())->get();
    }

    public function allDetailedCompleted(): Collection
    {
        return $this
            ->withDetailed($this->onlyCompleted(ChatHistory::query()))
            ->orderBy('chat_date', 'DESC')
            ->get();
    }

    public function searchCompletedChatHistories(ChatHistoryFilter $filter, ChatHistorySorter $sorter): Collection
    {
        $fieldName = $sorter->fieldName();
        if ('' !== $fieldName && \strpos($fieldName, '.')) {
            [$related, $field] = explode('.', $fieldName);
            $qb = ChatHistory::leftJoinRelationShip($related);
            $qb->orderByPowerJoins($fieldName, $sorter->sortOrder());
        } else {
            $qb = ChatHistory::query();
            $qb->orderBy(
                $fieldName,
                $sorter->sortOrder()
            );
        }

        if ($filter->datesExists()) {
            $qb = $this->withChatDateBetween($qb, $filter->from(), $filter->to());
        }
        if ($filter->customerServiceIdExists()) {
            $qb = $this->withCustomerSerivceId($qb, $filter->customerServiceId());
        }
        $qb = $this->withDetailed($this->onlyCompleted($qb));
        $qb->limit($filter->limit());

        return $qb->get();
    }

    public function addNotCompletedChatHistory(ChatHistory $chatHistory, ParticipantInterface $client): void
    {
        $chatHistory->completed = 0;
        $chatHistory->save();

        Redis::command('HSET', [self::NOT_COMPLETED_CHAT_HISTORIES_MAP, $client->identifier(), $chatHistory->id]);
    }

    public function addCompleted(ChatHistory $chatHistory, ParticipantInterface $client): void
    {
        $chatHistory->completed = 1;
        $chatHistory->save();

        if (Redis::command('HEXISTS', [self::NOT_COMPLETED_CHAT_HISTORIES_MAP, $client->identifier()])) {
            Redis::command('HDEL', [self::NOT_COMPLETED_CHAT_HISTORIES_MAP, $client->identifier(), $chatHistory->id]);
        }
    }

    public function notCompletedChatHistoryForClientParticipant(ParticipantInterface $client): ?ChatHistory
    {
        if (Redis::command('HEXISTS', [self::NOT_COMPLETED_CHAT_HISTORIES_MAP, $client->identifier()])) {
            $chatHistoryId = Redis::command(
                'HGET',
                [
                    self::NOT_COMPLETED_CHAT_HISTORIES_MAP,
                    $client->identifier()
                ]
            );

            return ChatHistory
                ::where('id', $chatHistoryId)
                ->where('completed', '=', 0)
                ->first();
        }

        return null;
    }

    public function notCompletedChatHistoryForClient(Client $client): ?ChatHistory
    {
        return ChatHistory::query()
            ->with('messages')
            ->where('client_id', '=', $client->id)
            ->where('completed', '=', 0)
            ->first();
    }

    public function clientHasNotCompletedChatHistory(Client $client): bool
    {
        return ChatHistory::query()
            ->where('client_id', '=', $client->id)
            ->where('completed', '=', 0)
            ->count() > 0;
    }

    public function lastCompletedChatHistory(Client $client): ?ChatHistory
    {
        return ChatHistory
            ::whereClientId($client->id)
            ->orderBy('chat_date', 'DESC')
            ->with('messages')
            ->first();
    }

    private function withDetailed(Builder $builder): Builder
    {
        return $builder->with(['client', 'operator', 'questionType', 'customerService', 'messages']);
    }

    private function withChatDateBetween(
        Builder $qb,
        \DateTimeImmutable $from,
        \DateTimeImmutable $to
    ): Builder {
        return $qb->whereBetween('chat_date', [$from, $to]);
    }

    private function withCustomerSerivceId(Builder $qb, int $customerServiceId): Builder
    {
        return $qb->where('customer_service_id', $customerServiceId);
    }

    private function onlyCompleted(Builder $qb): Builder
    {
        $qb->where('completed', '=', 1);

        return $qb;
    }
}
