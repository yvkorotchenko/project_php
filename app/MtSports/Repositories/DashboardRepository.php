<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\DashboardEntity;

class DashboardRepository
{
    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function totalStatistics(): array
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(
                '/crud/Information/total',
              [],
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return \get_object_vars($responseBody->data);
    }

    public function betAmountPlatform(): array
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(
                '/crud/Information/companyBet',
                [],
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function betAmountPlatformLists(): array {
        $responseBody = $this->responseParser->body(
            $this->client->get(
                '/platforms',
                [
                    'pageSize' => 37,
                ],
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data->result;
    }

    public function platformProfitLossPlayerBetting(int $id): array
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(
                '/crud/Information/betAndWin/' . $id,
                [],
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function rechargeWithdrawal(): array
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(
                '/crud/Information/rechargeAndWith',
                [],
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function platformLists() {
        $responseBody = $this->responseParser->body(
            $this->client->get(
                '',
                [],
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }
}