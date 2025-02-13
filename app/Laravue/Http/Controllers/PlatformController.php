<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Laravue\Http\Requests\PlatformCreateRequest;
use App\Laravue\Http\Requests\PlatformUpdateCategoriesRequest;
use App\Laravue\Http\Requests\PlatformUpdateRequest;
use App\Laravue\Repositories\PlatformRepository;
use App\Laravue\Services\PlatformService;
use App\Laravue\Services\ResponseFactory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class PlatformController
{
    public function __construct(
        private PlatformRepository $platformRepository,
        private ResponseFactory $responseFactory,
        private PlatformService $platformService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $paginatedData = $this->platformService->paginated(
                (int)$request->get('page', 1),
                (int)$request->get('limit', 1)
            );

            return $this->responseFactory->paginatedJson(
                $paginatedData->items(),
                $paginatedData->total()
            );
        } catch (\Throwable $e) {
            dd($e->getMessage());
            throw new BadRequestException();
        }
    }

    public function show(int $id): JsonResponse
    {
        $platform = $this->platformRepository->find($id);
        if (null === $platform) {
            throw new NotFoundHttpException('Entity not found');
        }

        return $this->responseFactory->json($platform);
    }

    public function store(PlatformCreateRequest $request): JsonResponse
    {
        try {
            return $this->responseFactory->json(
                $this->platformService->create(
                    $request->all()
                ),
                201
            );
        } catch (\Throwable $e) {
            dd($e->getMessage());
            throw new UnprocessableEntityHttpException();
        }
    }

    public function update(PlatformUpdateRequest $request): JsonResponse
    {
        try {
            return $this->responseFactory->json(
                $this->platformService->update($request->all())
            );
        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getFile(), $e->getLine());
            throw new UnprocessableEntityHttpException();
        }
    }

    public function destroy(int $id): Response
    {
        try {
            $isSuccesfullResult = $this->platformService->deleteById($id);
            if ($isSuccesfullResult) {
                return $this->responseFactory->noContent();
            } else {
                throw new UnprocessableEntityHttpException();
            }
        } catch (\Throwable $e) {
            throw new UnprocessableEntityHttpException();
        }
    }

    public function gameCategories(int $id): JsonResponse
    {
        try {
            return $this->responseFactory->json(
                $this->platformService->categoriesByPlatformId($id)
            );
        } catch (\Throwable $e) {
            dd($e->getMessage());
            throw new UnprocessableEntityHttpException();
        }
    }

    public function updateCategories(
        int $platformId,
        PlatformUpdateCategoriesRequest $request
    ): JsonResponse {
        try {
            $this->platformService->updateCategories(
                $platformId,
                $request->input('categories', [])
            );
            return $this->responseFactory->json([], 204);
        } catch (\Throwable $e) {
            dd($e->getMessage(), $e->getFile(), $e->getLine());
            throw new UnprocessableEntityHttpException();
        }
    }
}
