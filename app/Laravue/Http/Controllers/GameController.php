<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Laravue\Exceptions\ValidationException;
use App\Laravue\Http\Requests\GameImageUploadRequest;
use App\Laravue\Services\GameService;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class GameController
{
    public function __construct(
        private GameService $gameService,
        private ResponseFactoryService $responseFactory
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $paginated = $this->gameService->paginated(
            (int)$request->get('page', 1),
            (int)$request->get('limit', 10)
        );

        return $this->responseFactory->paginatedJson(
            $paginated->items(),
            $paginated->total()
        );
    }

    public function store(Request $request): JsonResponse
    {
        try {
            return $this->responseFactory->json(
                $this->gameService->create($request->all())
            );
        } catch (ValidationException $e) {
            return $this->responseFactory->validationErrorResponse(
                $e->errorMessages()
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->json(['error' => $e->getMessage()], 400);
        }
    }

    public function update(Request $request): JsonResponse
    {
        try {
            return $this->responseFactory->json(
                $this->gameService->update($request->all())
            );
        } catch (ValidationException $e) {
            return $this->responseFactory->error(
                $e->getMessage()
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->json(['error' => $e->getMessage()], 400);
        }
    }

    public function uploadImage(
        int $gameId,
        GameImageUploadRequest $request
    ): JsonResponse {
        try {
            return $this->responseFactory->json(
                $this->gameService->updateImage(
                    $gameId,
                    $request->file('image'),
                    $request->get('type')
                )
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->json(['error' => $e->getMessage()], 400);
        }
    }
}
