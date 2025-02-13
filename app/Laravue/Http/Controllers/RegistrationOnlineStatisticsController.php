<?php

namespace App\Laravue\Http\Controllers;

use App\Laravue\Services\ResponseFactoryService;
use App\MtSports\Repositories\RegistrationOnlineStatisticsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrationOnlineStatisticsController
{
    public function __construct(
        private ResponseFactoryService  $responseFactoryService,
        private RegistrationOnlineStatisticsRepository $repository,
    ) {
    }

    public function list(Request $reqeust): JsonResponse
    {
        try {
            return $this->responseFactoryService->json(
                $this->repository->list(
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
            return $this->responseFactoryService->json($this->repository->latest());
        } catch (\Throwable $e) {
            return $this->responseFactoryService->error($e->getMessage());
        }
    }
}
