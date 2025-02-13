<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\Laravue\Entities\Game;
use App\Laravue\Entities\GameCategory;
use App\MtSports\Repositories\GameCategoryRepository;
use App\MtSports\Repositories\GameRepository;
use Illuminate\Support\Str;
use Validator;

class GameCategoryService
{
    public function __construct(
        private GameCategoryRepository $gameCategoryRepository,
        private GameService $gameService,
        private GameRepository $gameRepository,
    ){}

    public function create(array $gameCategoryData): GameCategory
    {
         Validator::validate($gameCategoryData, [
             'identity' => 'sometimes|string',
             'name' => 'string|required',
             'nameLocal' => 'string|required',
             'sequence' => 'int|required',
             'visible' => 'bool|required',
         ]);

        if (!\array_key_exists('identity', $gameCategoryData)) {
            $gameCategoryData['identity'] = Str::snake($gameCategoryData['name']);
        }

        return $this->gameCategoryRepository->add(
            new GameCategory(
                0,
                $gameCategoryData['identity'],
                $gameCategoryData['name'],
                $gameCategoryData['nameLocal'],
                (int)$gameCategoryData['sequence'],
                (bool)$gameCategoryData['visible'],
            )
        );
    }

    public function update(array $gameCategoryData): GameCategory
    {
        Validator::validate($gameCategoryData, [
            'id' => 'int|required',
            'identity' => 'sometimes|string',
            'name' => 'string|required',
            'nameLocal' => 'string|required',
            'sequence' => 'int|required',
            'visible' => 'bool|required',
        ]);

        return $this->gameCategoryRepository->update(
            new GameCategory(
                $gameCategoryData['id'],
                $gameCategoryData['identity'],
                $gameCategoryData['name'],
                $gameCategoryData['nameLocal'],
                $gameCategoryData['sequence'],
                $gameCategoryData['visible'],
            )
        );
    }

    public function delete(int $id): void
    {
        $this->gameCategoryRepository->delete($id);

        $games = $this->gameRepository->findByCategoryId($id);
        /** @var Game $game */
        foreach ($games as $game) {
            $this->gameService->delete($game->id);
        }
    }
}
