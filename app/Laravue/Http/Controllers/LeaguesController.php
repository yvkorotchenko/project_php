<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Laravue\Services\LeaguesService;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LeaguesController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private LeaguesService $leaguesService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('limit', 20);

        try {
            $listPagination = $this->leaguesService->listPagination($page, $size);

            return $this->responseFactory->paginatedJson(
                $listPagination->result,
                $listPagination->total,
            );
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function update(int $id, Request $request)/*: JsonResponse*/
    {
        try {
            $this->leaguesService->update($request->all());
            return $this->responseFactory->formattedJson(
                [
                    'message' => 'Leagues update successfully',
                ],
            );
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function previousLeagueSelection(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedSTDClassJson(
                $this->leaguesService->previousLeagueSelection(),
            );
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
