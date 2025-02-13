<?php

namespace App\Chat\Services;

use App\Chat\Entities\Participants\ParticipantInterface;
use Illuminate\Contracts\Redis\Connection as RedisConnection;

class ClientLanguageRepository
{
    private const HASH_NAME = 'client_lang';
    private const FALLBACK_LANG = 'zn';

    public function __construct(private RedisConnection $connection)
    {}

    public function add(ParticipantInterface $participant, string $language): void
    {
        if (!\in_array($language, $this->availableLanguages())) {
            $language = self::FALLBACK_LANG;
        }
        $this->connection->command('HSET', [self::HASH_NAME, $participant->identifier(), $language]);
    }

    public function del(ParticipantInterface $participant): void
    {
        if ($this->connection->command('HEXISTS', [self::HASH_NAME, $participant->identifier()])) {
            $this->connection->command('HDEL', [self::HASH_NAME, $participant->identifier()]);
        }
    }

    public function getForClient(ParticipantInterface $participant): string
    {
        $result = null;

        if ($this->connection->command('HEXISTS', [self::HASH_NAME, $participant->identifier()])) {
            $result = $this->connection->command('HGET', [self::HASH_NAME, $participant->identifier()]);
        }

        if (!$result) {
            $this->add($participant, self::FALLBACK_LANG);
            $result = self::FALLBACK_LANG;
        }

        return $result;
    }

    public function availableLanguages(): array
    {
        return ['en', 'zh'];
    }
}
