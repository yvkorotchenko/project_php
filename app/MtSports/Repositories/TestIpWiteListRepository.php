<?php
declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\TestIpWiteListEntitie;

class TestIpWiteListRepository
{
    private const BASE_PATH = '';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function create(array $ipWiteLists): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->post(
                self::BASE_PATH,
                [],
                new TestIpWiteListEntitie($ipWiteLists),
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessful response code ' . $responseCode);
        }
    }
}