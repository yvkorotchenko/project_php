<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\Laravue\Entities\ActivityConfigurationEntities;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class ActivityConfigurationRepository
{
    private const BASE_PATH = '/crud/activities/%d';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function activityConfiguration(): \StdClass
    {
        $responseBody =  $this->responseParser->body(
            $this->client->get(
                \str_replace('%d', '', self::BASE_PATH),
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function updateActivityConfiguration(array $activity): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->put(
                \sprintf(self::BASE_PATH, $activity['id']),
                [],
                new ActivityConfigurationEntities($activity),
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code: ' . $responseCode);
        }
    }
}