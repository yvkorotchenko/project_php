<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\ForbiddenIpLoginEntitie;

class ForbiddenIpLoginRepository
{
    private const BASE_PATH = '';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function create(array $forbiddenIpLogin): void
    {
        $forIp = new ForbiddenIpLoginEntitie($forbiddenIpLogin);
        $responseCode = $this->responseParser->statusCode(
            $this->client->post(
                self::BASE_PATH,
                [],
                $forIp,
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code ' . $responseCode);
        }
    }
}
