<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\Laravue\Entities\LeaguesEntities;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\LeaguesEntitie;

class LeaguesRepository
{
    private const BASE_PATH = '/crud/matches/leagues%s';
    private const PREVIOUS_LEAGUE_SELECTION_PATH = '/crud/matches/leagues/bi';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function leagues(int $page = 1, int $size = 20): \StdClass
    {
        $query = '?size=' . $size . '&page=' . $page;

        $responseBody = $this->responseParser->body(
            $this->client->get(
                \sprintf(self::BASE_PATH, $query),
                [
                  'page' => $page,
                  'size' => $size,
                ]
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function previousLeagueSelection(): \StdClass
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::PREVIOUS_LEAGUE_SELECTION_PATH,
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function updateLeagues(array $league): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->put(
                \sprintf(self::BASE_PATH, ''),
                [],
                new LeaguesEntitie($league),
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessful response code: ' . $responseCode);
        }
    }
}
