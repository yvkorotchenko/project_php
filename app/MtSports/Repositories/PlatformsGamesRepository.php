<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class PlatformsGamesRepository
{
    private const BASE_PATH = '/platforms/games?pageSize=%d';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function game(int $page = 1, int $size = 20): \StdClass
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(
                \sprintf(self::BASE_PATH, $size),
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
}