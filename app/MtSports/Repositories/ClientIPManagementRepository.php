<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\MtSports\Entities\TaskManagement;

class ClientIPManagementRepository
{
    private const BASE_PATH = '/crud/management';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {}

    public function list(int $page = 1, int $size = 20)
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
        
        return $responseBody;
    }

    public function update(int $id, bool $state)
    {

        $responseBody = $this->responseParser->body(
            $this->client->put(
                self::BASE_PATH . '/' . $id,
                [
                    'id' => $id,
                    'state' => $state
                ],
            ),
        );
        
        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody;
    }
}