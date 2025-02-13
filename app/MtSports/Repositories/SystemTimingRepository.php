<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Entities\SystemTimingEntity;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class SystemTimingRepository
{
    private const BASE_PATH = '/crud/ticker';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function systemTiming(int $page = 20, int $size = 1): \StdClass
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

    public function createSystemTiming(array $systemTiming): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->post(
                self::BASE_PATH,
                [],
                new SystemTimingEntity($systemTiming),
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code' . $responseCode);
        }
    }

    public function updateSystemTiming(array $systemTiming): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->put(
                self::BASE_PATH,
                [],
                new SystemTimingEntity($systemTiming),
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code' . $responseCode);
        }
    }

    public function deleteSystemTiming(string $systemTimingIds): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->delete(
                self::BASE_PATH,
                [
                    'ids' => $systemTimingIds,
                ],
            ),
        );

        if ($responseCode !== 204) {
            throw new \Exception('Unsuccessfull response code' . $responseCode);
        }
    }
}
