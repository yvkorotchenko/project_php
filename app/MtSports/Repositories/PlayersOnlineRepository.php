<?php

namespace App\MtSports\Repositories;

use App\MtSports\Entities\UserOnlineStatisticEntity;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use \DateTimeImmutable;

class PlayersOnlineRepository
{
    private const BASE_URL = '/statistics/playing-online';
    private const BASE_URL_USERS_INFO = '/statistics/playing-online/users/game/%d/users-info';
    private const LIST_KEY = 'userOnlineInGameByDateData';
    private const BASE_URL_USERS_ONLINE_BY_DEVICES = '/statistics/playing-online/users/by-devices';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser
    ) {
    }

    public function list(string $from, string $to): UserOnlineStatisticEntity
    {
        $from = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $from);
        $to = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $to);

        if ($from === false) {
            throw new \Exception('Invalid format for field `from`');
        }

        if ($to === false) {
            throw new \Exception('Invalid format for field `to`');
        }
        $from = $from->getTimestamp();
        $to = $to->getTimestamp();

        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::BASE_URL,
                ['from' => $from, 'to' => $to]
            )
        );
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return new UserOnlineStatisticEntity(
            $responseBody->data->{self::LIST_KEY}
        );
    }

    public function latest(): UserOnlineStatisticEntity
    {
        $from = new \DateTime();
        $from->modify('+ 1 hour');
        $to = new \DateTime();
        $to->modify('- 1 hour');

        return $this->list($from->format('Y-m-d H:00:00'), $to->format('Y-m-d H:00:00'));
    }

    public function usersInfoByGameId(int $gameId, int $page, int $size): \StdClass
    {
        $responseBody = $this->responseParser->body($this->client->get(
            \sprintf(self::BASE_URL_USERS_INFO, $gameId),
            ['page' => $page, 'size' => $size]
        ));
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function usersOnlineByDevices(): \StdClass
    {
        $responseBody = $this->responseParser->body($this->client->get(
            self::BASE_URL_USERS_ONLINE_BY_DEVICES
        ));
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }
}
