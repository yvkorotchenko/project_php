<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;


use App\MtSports\Entities\RewardsManagement;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class RewardsManagementRepository
{
    private const BASE_PATH = '/crud/rewards';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function rewards(int $page = 1, int $size = 20): \StdClass
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH,
                [
                    'page' => $page,
                    'size' => $size,
                ],
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function create(array $rewards): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->post(
                self::BASE_PATH,
                [],
                new RewardsManagement($rewards),
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code ' . $responseCode);
        }
    }

    public function update(int $id, array $rewards): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->put(
                self::BASE_PATH . '/' . $id,
                [],
                new RewardsManagement($rewards)
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code ' . $responseCode);
        }
    }

    public function delete(string $rewardsIds): void
    {
        $response = $this->client->deleteByBodyString(
            self::BASE_PATH,
            [],
            $rewardsIds
        );

        $responseCode = $response->getStatusCode();

        if ($responseCode !== 204) {
            throw new \Exception('Unsuccessfull response code' . $responseCode);
        }
    }
}
