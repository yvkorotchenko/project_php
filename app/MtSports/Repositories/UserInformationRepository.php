<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class UserInformationRepository
{
    private const USERS_LOGIN_BASE_PATH = '/users/login/count?day=%s';
    private const USERS_REGISTERED_BASE_PATH = '/users/registered/count?day=%s';
    private const USERS_FINANCE_INFO_REPOSITORY = '/crud/users/%d/info/finance';

    private $days = [];

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseparser $responseParser,
    ) {
        $this->days = $this->dateInFiveDays();
    }

    public function userRegistration(): array
    {
        return $this->dataInfiveDays(self::USERS_REGISTERED_BASE_PATH);
    }

    public function userLogin(): array
    {
        return $this->dataInFiveDays(self::USERS_LOGIN_BASE_PATH);
    }

    public function newUserCount(): array
    {
        $now = date('Y-m-d');

        $responseBody = $this->responseParser->body(
            $this->client->get(
                \sprintf(self::USERS_REGISTERED_BASE_PATH, $now),
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return [
            'date' => $now,
            'count' => $responseBody->data
        ];
    }

    public function dataInFiveDays(string $path, int $i = 0): array
    {
        $data = $this->listDateInFiveDays();

        for($i, $len = count($this->days); $i < $len; $i++) {
            $responseBody = $this->responseParser->body(
                $this->client->get(
                    \sprintf($path, $this->days[$i]),
                ),
            );

            if ($responseBody->code !== '200') {
                throw new \Exception($responseBody->message);
            }

            $data[$i]['date'] = $this->days[$i];
            $data[$i]['count'] = $responseBody->data;
        }

        return $data;
    }

    public function dateInFiveDays(): array
    {
        $days = [];

        foreach (range(0, 5) as $day) {
            $days[] = (new \DateTime('-' . $day . ' day'))->format('Y-m-d');
        }

        return $days;
    }

    public function listDateInFiveDays(): array
    {
        return [
            0 => [
                'date' => '',
                'count' => 0,
            ],
            1 => [
                'date' => '',
                'count' => 0,
            ],
            2 => [
                'date' => '',
                'count' => 0,
            ],
            3 => [
                'date' => '',
                'count' => 0,
            ],
            4 => [
                'date' => '',
                'count' => 0,
            ],
            5 => [
                'date' => '',
                'count' => 0,
            ],
        ];
    }

    public function financeInfo(int $uid): \StdClass
    {
        $response = $this->client->get(\sprintf(self::USERS_FINANCE_INFO_REPOSITORY, $uid));
        $body = $this->responseParser->body($response);
        if ($body->code !== '200') {
            throw new \Exception($body->message);
        }
        if (!\property_exists($body, 'data') || !\is_object($body->data)) {
            throw new \Exception('Response body is empty');
        }

        return $body->data;
    }
}