<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\Laravue\Entities\PaginatedCollection;
use App\Laravue\Entities\Platform;
use App\Laravue\Entities\PlatformGameCategory;
use App\MtSports\Repositories\GameCategoryRepository;
use App\MtSports\Repositories\PlatformGameCategoryRepository;
use App\MtSports\Repositories\PlatformRepository;
use Illuminate\Validation\Factory as ValidatorFactory;

class PlatformService
{
    public function __construct(
        private ValidatorFactory $validatorFactory,
        private PlatformRepository $platformRepository,
        private PlatformGameCategoryRepository $platformGameCategoryRepository,
        private GameCategoryService $gameCategoryService,
    ) {}

    public function create(array $entityProperties): ?Platform
    {
        $this->validatorFactory->validate($entityProperties, [
            'name' => 'required|string',
            'visible' => 'required|bool',
            'parentName' => 'required|string',
        ]);

        return $this->platformRepository->add(new Platform(
            0,
            $entityProperties['name'],
            $entityProperties['visible'],
             $entityProperties['parentName'],
        ));
    }

    public function update(array $entityProperties): ?Platform
    {
        $this->validatorFactory->validate($entityProperties, [
            'id' => 'required|int',
            'name' => 'required|string',
            'visible' => 'required|bool',
            'parentName' => 'required|string',
          ]);

        return $this->platformRepository->update(new Platform(
            $entityProperties['id'],
            $entityProperties['name'],
            $entityProperties['visible'],
            $entityProperties['parentName'],
        ));
    }

    public function deleteById(int $id): bool
    {
        $result = $this->platformRepository->removeById($id);

        foreach ($this->platformGameCategoryRepository->platformCategories($id) as $category) {
            $this->gameCategoryService->delete($category->id());
        }

        return $result;
    }

    public function paginated(int $page, int $perPage): PaginatedCollection
    {
        if ($page < 1) {
            throw new \LogicException('Invalid page number');
        }
        if ($perPage < 1) {
            throw new \LogicException('Invalid perPage number');
        }

        $result = $this->platformRepository->findWithOffset($page, $perPage);

        return new PaginatedCollection(
            $result['data'],
            $result['total'],
            $page
        );
    }

    public function categoriesByPlatformId(int $platformId): array
    {
        return $this->platformGameCategoryRepository->platformCategories($platformId);
    }

    public function updateCategories(int $platfromId, array $gameCategories): void
    {
        $prevCategoriesIds = \array_map(
            fn(PlatformGameCategory $cat) => $cat->id(),
            $this->platformGameCategoryRepository->platformCategories($platfromId)
        );

        if (\count($gameCategories) === 0) {
            $this->platformGameCategoryRepository->deleteCategories($platfromId,$prevCategoriesIds);
        } else {
            $newCategoriesIds = \array_column($gameCategories, 'id', 'id');
            $categoriesToDelete = [];
            $categoriesToCreate = $newCategoriesIds;
            $categoriesToUpdate = [];
            foreach ($prevCategoriesIds as $prevCategoryId) {
                if (\in_array($prevCategoryId, $newCategoriesIds)) {
                    $categoriesToUpdate[] = $prevCategoryId;
                } else {
                    $categoriesToDelete[] = $prevCategoryId;
                }
                unset($categoriesToCreate[$prevCategoryId]);
            }
            if (\count($categoriesToDelete) > 0) {
                $this->platformGameCategoryRepository->deleteCategories($platfromId, $categoriesToDelete);
            }
            if (\count($categoriesToCreate) > 0) {
                $categories = \array_filter($gameCategories, fn($cat) => \in_array($cat['id'], $categoriesToCreate));
                foreach ($categories as $category) {
                    $this->platformGameCategoryRepository->createCategory(
                        $platfromId,
                        $category['id'],
                        $category['sequence']
                    );
                }
            }
            if (\count($categoriesToUpdate) > 0) {
                $categories = \array_filter($gameCategories, fn($cat) => \in_array($cat['id'], $categoriesToUpdate));

                $this->platformGameCategoryRepository->updateCategories(
                    $platfromId,
                    $categories
                );
            }
        }
    }
}
