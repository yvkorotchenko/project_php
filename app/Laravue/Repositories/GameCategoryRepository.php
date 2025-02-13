<?php

declare(strict_types=1);

namespace App\Laravue\Repositories;

use App\Laravue\Entities\GameCategory;
use App\Laravue\Services\EntityMapper;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class GameCategoryRepository
{
    private const BASE_PATH = '/platforms/categories';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
        private EntityMapper $entityMapper
    ) {}

    public function all(): array
    {
        $result = [];

        $body = $this->responseParser->body(
            $this->client->get(self::BASE_PATH)
        );
        foreach ($body->data->result as $row) {
            $result[] = $this->entityMapper->map($row, GameCategory::class);
        }

        return $result;
    }
}
