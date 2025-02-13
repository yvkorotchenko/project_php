<?php

declare(strict_types=1);

namespace App\Laravue\Repositories;

use App\Laravue\Entities\PlatformGameCategory;
use App\Laravue\Services\EntityMapper;
use App\MtSports\Entities\PlatformCategories;
use App\MtSports\Entities\PlatformCategory;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class PlatformGameCategoryRepository
{
    private const BASE_PATH = '/platforms/%d/categories';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
        private EntityMapper $entityMapper
    ) {}

    /**
     * @return PlatformGameCategory[]
     */
    public function platformCategories(int $platformId): array
    {
        $result = [];

        $body = $this->responseParser->body(
            $this->client->get(
                \sprintf(self::BASE_PATH, $platformId)
            )
        );
        foreach ($body->data as $row) {
            $result[] = $this->entityMapper->instantianteByMap(
                $row,
                [
                    'id' => 'id',
                    'name' => 'name',
                    'seq' => 'sequence'
                ],
                PlatformGameCategory::class
            );
        }

        return $result;
    }

    public function updateCategories(int $platformId, array $categoriesData): void
    {
        $categoriesData = \array_map(
            fn($el) => ['id' => $el['id'],
                        'seq' => $el['sequence']],
            $categoriesData
        );

        $responseCode = $this->responseParser->statusCode(
            $this->client->put(
                \sprintf(self::BASE_PATH, $platformId),
                [],
                new PlatformCategories($categoriesData)
            )
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code: ' . $responseCode);
        }
    }

    public function deleteCategories(int $platformId, array $categoriesIds): void
    {
        foreach ($categoriesIds as $categoryId) {
            $responseCode = $this->responseParser->statusCode(
                $this->client->delete(
                    \sprintf(self::BASE_PATH, $platformId),
                    ['cid' => $categoryId]
                )
            );
            if ($responseCode !== 204) {
                throw new \Exception('Unsuccessfull response code: ' . $responseCode);
            }
        }
    }

    public function createCategory(int $platformId, int $categoryId, int $sequence): void
    {
         $this->client->post(
             \sprintf(self::BASE_PATH, $platformId),
             [],
             new PlatformCategory($categoryId, $sequence)
        );
    }
}
