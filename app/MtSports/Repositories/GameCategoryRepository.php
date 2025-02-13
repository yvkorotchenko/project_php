<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\Laravue\Entities\GameCategory;
use App\Laravue\Services\EntityMapper;
use App\MtSports\Entities\GameCategory as MtGameCategory;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class GameCategoryRepository
{
    private const BASE_PATH = '/platforms/categories';
    private const CONNECTION_NAME = 'mtsports';
    private const TABLE_NAME = 'game_category';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
        private EntityMapper $entityMapper,
    ) {}

    public function all(): array
    {
        $result = [];

        $body = $this->responseParser->body(
            $this->client->get(self::BASE_PATH)
        );
        foreach ($body->data->result as $row) {
            $result[] = $this->mapStdClassToGameCategory($row);
        }

        return $result;
    }

    public function add(GameCategory $category): GameCategory
    {

        $body = $this->responseParser->body(
            $this->client->post(
                self::BASE_PATH,
                [],
                $this->entityMapper->instantiateByMap(
                    $category,
                    [
                        'id' => 'id',
                        'name' => 'name',
                        'nameLocal' => 'nameLocal',
                        'sequence' => 'seq',
                        'identity' => 'identity',
                        'visible' => 'isOpen',
                    ],
                    MtGameCategory::class
                )
            )
        );

        return $this->mapStdClassToGameCategory($body->data);
    }

    // TODO: move to restAPI when mt complete endpoints
    public function update(GameCategory $category): GameCategory
    {
       $this->qb()
            ->where('id', $category->id)
            ->update([
                'name' => $category->name,
                'name_local' => $category->nameLocal,
                'identity' => $category->identity,
                'seq' => $category->sequence,
                'is_open' => $category->visible,
            ]);

        return $this->find($category->id);
    }

    // TODO: move to restAPI when mt complete endpoints
    public function find(int $id): ?GameCategory
    {
        $result = $this->qb()->find($id);
        if (!$result) {
            return null;
        }

        return new GameCategory(
            $result->id,
            $result->identity,
            $result->name,
            $result->name_local,
            $result->seq,
            $result->is_open === 1,
        );
    }

    public function delete(int $id): void
    {
        $this->qb()->delete($id);
    }

    public function findByPlatformId(int $id): array
    {
        $rows = $this->qb()->where('company_id', '=', $id)->get();
        $result = [];
        foreach ($rows as $row) {
            $result[] = $this->mapStdClassToGameCategory($row);
        }

        return $result;
    }

    private function qb(): Builder
    {
        return  DB::connection(self::CONNECTION_NAME)->table(self::TABLE_NAME);
    }

    private function mapStdClassToGameCategory(object $instance): GameCategory
    {
        return $this->entityMapper->instantiateByMap(
            $instance,
            [
                'id' => 'id',
                'name' => 'name',
                'nameLocal' => 'nameLocal',
                'seq' => 'sequence',
                'identity' => 'identity',
                'isOpen' => 'visible',
            ],
            GameCategory::class
        );
    }

    private function mapBiCategoryToMtCategory(GameCategory $category): MtGameCategory
    {
        return $this->entityMapper->instantiateByMap(
            $category,
            [
                'id' => 'id',
                'name' => 'name',
                'nameLocal' => 'nameLocal',
                'sequence' => 'seq',
                'identity' => 'identity',
                'visible' => 'isOpen',
            ],
            MtGameCategory::class
        );
    }
}
