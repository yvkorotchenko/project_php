<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\SevenDaysWelfareEntities;
use App\MtSports\Entities\SevenDaysWelfareDeleteIdsEntities;

class SevenDaysWelfareRepository
{
    private const BASE_PATH = '/crud/welfares';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function sevenDaysWelfare(int $page = 1, int $size = 20): \StdClass
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

    public function create(array $sevenDaysWelfare): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->post(
                self::BASE_PATH,
                [],
                new SevenDaysWelfareEntities($sevenDaysWelfare),
            ),
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessful response code ' . $responseCode);
        }
    }

    public function update(int $id, array $sevenDaysWelfare): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->put(
                self::BASE_PATH . '/' . $id,
                [],
                new SevenDaysWelfareEntities($sevenDaysWelfare)
            )
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code ' . $responseCode);
        }
    }

    public function delete(string $sevenDaysWelfareIds): void
    {
        $responseCode = $this->responseParser->statusCode(
            $this->client->delete(
                self::BASE_PATH,
                [],
                new SevenDaysWelfareDeleteIdsEntities($sevenDaysWelfareIds),
            ),
        );

        if ($responseCode !== 204) {
            throw new \Exception('Unsuccessfull response code ' . $responseCode);
        }
    }
}