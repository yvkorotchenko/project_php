<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Services\ActivityConfigurationServices;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;

class ActivityConfigurationController extends Controller
{
    public function __construct(
        private ActivityConfigurationServices $activityConfigurationServices,
        private ResponseFactoryService $responseFactory,
    ) {}

    public function index(): JsonResponse
    {
        $listPagination = $this->activityConfigurationServices->listPagination();

        try {
            return $this->responseFactory->paginatedJson(
                $listPagination->result,
                $listPagination->total,
            );
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function update(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson();
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
