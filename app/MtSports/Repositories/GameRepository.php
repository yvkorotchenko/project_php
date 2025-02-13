<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\Laravue\Entities\Game;
use App\MtSports\Entities\Game as MtGame;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;

class GameRepository
{
    private const BASE_PATH = '/platforms/games';


    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
        private PlatformRepository $platformRepository,
        private GameCategoryRepository $gameCategoryRepository,
    ) {
    }

    public function paginated(int $page, int $size)
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(
                self::BASE_PATH,
                [
                    'pageNo' => $page,
                    'pageSize' => $size,
                ],
            ),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        $data = $responseBody->data;
        $result = [
            'data' => [],
            'total' => $data->total,
        ];
        foreach ($data->result as $one) {
            $result['data'][] = $this->mapRowToEntity($one);
        }

        return $result;
    }

    public function create(Game $game): void
    {
        $responseBody = $this->responseParser->body($this->client->post(
            self::BASE_PATH,
            [],
            new MtGame(
                $game->id,
                $game->gameCode,
                $game->name,
                $game->nameLocal,
                $game->icon,
                $game->coverPic,
                $game->sequence,
                $game->visible,
                $game->canTry,
                $game->category->id,
                $game->platform->id,
                $game->platform->name,
            )
        ));

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }
    }

    public function update(Game $game): void
    {
        $responseBody = $this->responseParser->body($this->client->put(
            self::BASE_PATH,
            [],
            new MtGame(
                $game->id,
                $game->gameCode,
                $game->name,
                $game->nameLocal,
                $game->icon,
                $game->coverPic,
                $game->sequence,
                $game->visible,
                $game->canTry,
                $game->category->id,
                $game->platform->id,
                $game->platform->name,
            )
        ));

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }
    }

    public function find(int $id)
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(self::BASE_PATH . "/$id"),
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $this->mapRowToEntity($responseBody->data);
    }

    private function mapRowToEntity(object $row): Game
    {
        $category = $this->gameCategoryRepository->find($row->categoryId);
        if (null === $category) {
            throw new \LogicException('Undefined category for game');
        }

        $platform = $this->platformRepository->find($row->companyId);
        if (null === $platform) {
            throw new \LogicException('Undefined platform for game');
        }

        return new Game(
            $row->id,
            $row->identity,
            $row->name,
            $row->nameLocal,
            $row->icon,
            $row->coverPic,
            $row->seq,
            $row->isOpen === 'Y',
            $row->isTry === 'Y',
            $category,
            $platform,
        );
    }
}
