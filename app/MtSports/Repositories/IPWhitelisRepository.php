<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\Laravue\Entities\IPWhitelisEntities;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class IPWhitelisRepository
{
    private const BASE_PATH = '/crud/whitelist';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function listPagination(int $page = 1, int $size = 20): \StdClass
    {
        $responseBody =  $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH,
                [
                    'page' => $page,
                    'size' => $size
                ],
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function store(array $data): void
    {
        $responseBody =  $this->responseParser->body(
            $this->client->post(
                self::BASE_PATH,
                [],
                new IPWhitelisEntities(
                    $data['ipAddress'],
                    $data['operatorId'],
                    $data['remark'],
                )
            ),
        );
        
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

    }

    public function destroy($id): void
    {
        $responseBody =  $this->responseParser->body(
            $this->client->delete(
                self::BASE_PATH,
                [
                    'ids' => $id
                ],
            ),
        );

        if ($responseBody->code !== '204') {
            throw new \Exception($responseBody->message);
        }
    }

}