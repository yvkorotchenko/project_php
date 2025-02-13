<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Entities\SportBookManagement;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class SportBookManagementRepository
{
    private const BASE_PATH_GET = '/matches/leagues/bi';
    private const BASE_PATH_SET = '/matches/leagues';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function getAllLeagues(): \StdClass
    {
        $responseBody = $this->responseParser->body(
            $this->client->get( self::BASE_PATH_GET ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function setAllLeagues(array $leagues): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->put(
                self::BASE_PATH_SET,
                [],
                new SportBookManagement($leagues)
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessful response code: ' . $responseCode);
        }
    }
}