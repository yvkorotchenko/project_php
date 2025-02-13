<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Services\PlatformsGamesService;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlatformsGamesController extends Controller
{
    public function __construct(
        private PlatformsGamesService $platformsGamesService,
        private ResponseFactoryService $responseFactory,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('limit', 20);

        $listPagination = $this->platformsGamesService->listPagination($page, $size);

        try {
            return $this->responseFactory->paginatedJson(
                $listPagination->result,
                $listPagination->total,
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
