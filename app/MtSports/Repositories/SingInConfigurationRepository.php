<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\SingInConfiguration;

class SingInConfigurationRepository
{

    private const BASE_PATH = '/crud/activities/signin';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function singin(): object
    {
        $responseCode = $this->responseParser->body(
            $this->client->get(self::BASE_PATH)
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code: ' . $responseCode);
        }

        return $responseCode->data;
    }

    /**
     * @throws \JsonException
     */
    public function updateSingin(array $singin): string
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->put(
                self::BASE_PATH,
                [],
                new SingInConfiguration($singin)
            )
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code: ' . $responseCode);
        }

        return 'Update success response code: ' . $responseCode;
    }
}
