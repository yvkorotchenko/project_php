<?php

namespace App\Laravue\Http\Controllers;

use App\Laravue\Services\ResponseFactoryService;
use App\MtSports\Repositories\PlayersOnlineRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PlayersOnlineStatisticsController
{
    public function __construct(
        private ResponseFactoryService  $responseFactoryService,
        private PlayersOnlineRepository $playersOnlineRepository
    )
    {}

    public function list(Request $reqeust): JsonResponse
    {
        try {
            return $this->responseFactoryService->json(
                $this->playersOnlineRepository->list(
                    $reqeust->get('from', ''),
                    $reqeust->get('to', '')
                )
            );
        } catch (\Throwable $e) {
            return $this->responseFactoryService->error($e->getMessage());
        }
    }

    public function latestList(): JsonResponse
    {
        try {
            return $this->responseFactoryService->formattedObject(
                $this->playersOnlineRepository->latest()
            );
        } catch (\Throwable $e) {
            return $this->responseFactoryService->error($e->getMessage());
        }
    }

    public function playersInfo(Request $request, int $gameId): JsonResponse
    {
        try {
            $result = $this->playersOnlineRepository->usersInfoByGameId(
                $gameId,
                $request->get('page', 1),
                $request->get('limit', 10)
            );
            return $this->responseFactoryService->paginatedJson($result->result, $result->totalItems);
        } catch (\Throwable $e) {
            return $this->responseFactoryService->error($e->getMessage());
        }
    }

    public function playersCountByDevices(): JsonResponse
    {
        try {
            return $this->responseFactoryService->json($this->playersOnlineRepository->usersOnlineByDevices());
        } catch (\Throwable $e) {
            return $this->responseFactoryService->error($e->getMessage());
        }
    }
}
