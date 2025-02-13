<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\Banner;

class BannerRepository
{
    private const BASE_PATH = '/crud/game-banners';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function banner(int $page = 1, int $size = 20): \stdClass
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

    public function create(array $banner): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->post(
                self::BASE_PATH,
                [],
                new Banner($banner),
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code ' . $responseCode);
        }
    }

    public function update(int $id, array $banner): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->put(
                self::BASE_PATH . '/' . $id,
                [],
                new Banner($banner),
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code' . $responseCode);
        }
    }

    public function delete(string $bannersIds): void
    {
        $response = $this->client->delete(
            self::BASE_PATH,
            [
                'ids' => $bannersIds,
            ]
        );

        $responseCode = $response->getStatusCode();

        if ($responseCode !== 204) {
            throw new \Exception('Unsuccessfull response code' . $responseCode);
        }
    }
}
