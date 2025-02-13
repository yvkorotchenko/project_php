<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Laravue\Http\Requests\GameCategoryCreateRequest;
use App\Laravue\Http\Requests\GameCategoryUpdateRequest;
use App\MtSports\Repositories\GameCategoryRepository;
use App\Laravue\Services\GameCategoryService;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class GameCategoryController
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private GameCategoryRepository $gameCategoryRepository,
        private GameCategoryService $gameCategoryService
    ) {}

    public function index(): JsonResponse
    {
        return $this->responseFactory->paginatedJson(
            $this->gameCategoryRepository->all()
        );
    }

    public function store(GameCategoryCreateRequest $request): JsonResponse
    {
        return $this->responseFactory->json(
            $this->gameCategoryService->create($request->all())
        );
    }

    public function update(GameCategoryUpdateRequest $request): JsonResponse
    {
        return $this->responseFactory->json(
            $this->gameCategoryService->update($request->all())
        );
    }

    public function destroy(int $id): Response
    {
        try {
            $this->gameCategoryService->delete($id);
            return $this->responseFactory->noContent();
        } catch (\Throwable $e) {
            throw new UnprocessableEntityHttpException();
        }
    }
}
