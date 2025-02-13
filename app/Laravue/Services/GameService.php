<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\Laravue\Entities\Game;
use App\Laravue\Entities\GameCategory;
use App\Laravue\Entities\PaginatedCollection;
use App\Laravue\Entities\Platform;
use App\Laravue\Validators\ArrayValidator;
use App\MtSports\Repositories\GameRepository;
use Illuminate\Http\UploadedFile;

class GameService
{
    public function __construct(
        private GameRepository $gameRepository,
        private EntityMapper $entityMapper,
        private StaticStorageService $storageService,
    ) {
    }

    public function paginated(int $page, int $perPage): PaginatedCollection
    {
        $data = $this->gameRepository->paginated($page, $perPage);
        return new PaginatedCollection($data['data'], $data['total'], $page);
    }

    public function create(array $gameData): void
    {
        (new ArrayValidator([
            'gameCode'    => 'string|required',
            'name'        => 'string|required',
            'nameLocal'   => 'string|required',
            'icon'        => 'string|sometimes|nullable',
            'coverPic'    => 'string|sometimes|nullable',
            'sequence'    => 'int|required',
            'visible'     => 'bool|required',
            'canTry'      => 'bool|required',
            'category.id'  => 'int|required',
            'platform.id'  => 'int|required',
        ]))->validate($gameData);
        $gameData['id'] = 0;
        if (!\array_key_exists('icon', $gameData) || null === $gameData['icon']) {
            $gameData['icon'] = '';
        }
        if (!\array_key_exists('coverPic', $gameData) || null === $gameData['coverPic']) {
            $gameData['coverPic'] = '';
        }

        $this->entityMapper->instantiateFromArray(
            $gameData,
            Game::class,
            ['category' => GameCategory::class, 'platform' => Platform::class]
        );
    }

    public function update(array $gameData): void
    {
        (new ArrayValidator(
            \array_merge(
                $this->validationRules(),
                ['id' => 'int|required']
            )
        ))->validate($gameData);
        if (!\array_key_exists('coverPic', $gameData) || null === $gameData['coverPic']) {
            $gameData['coverPic'] = '';
        }
        if (!\array_key_exists('icon', $gameData) || null === $gameData['icon']) {
            $gameData['icon'] = '';
        }

        $this->gameRepository->update(
            $this->entityMapper->instantiateFromArray(
                $gameData,
                Game::class,
                ['category' => GameCategory::class, 'platform' => Platform::class]
            )
        );
    }

    public function updateImage(int $id, UploadedFile $file, string $type): string
    {
        $game = $this->gameRepository->find($id);
        if (null === $game) {
            throw new \LogicException('Game does not exists');
        }

        if ($type !== 'icon' && $type !== 'coverPic') {
            throw new \LogicException('Invalid field');
        }

        $imageUrl = $this->storageService->uploadImage($file);

        if ($type === 'icon') {
            $game->icon = $imageUrl;
        } elseif ($type === 'coverPic') {
            $game->coverPic = $imageUrl;
        }
        $this->gameRepository->update($game);

        return $imageUrl;
    }

    private function validationRules(): array
    {
        return [
            'gameCode'    => 'string|required',
            'name'        => 'string|required',
            'nameLocal'   => 'string|required',
            'icon'        => 'string|nullable',
            'coverPic'    => 'string|nullable',
            'sequence'    => 'int|required',
            'canTry'      => 'bool|required',
            'category.id'  => 'int|required',
            'platform.id'  => 'int|required',
        ];
    }
}
