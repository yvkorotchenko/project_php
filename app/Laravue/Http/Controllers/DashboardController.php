<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Laravue\Services\DashboardService;
use App\Laravue\Services\ResponseFactoryService;

class DashboardController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private DashboardService $dashboard,
    ) {}

    public function totalStatistics(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                $this->dashboard->totalStatistics(),
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function betAmountPlatform(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                $this->dashboard->betAmountPlatform(),
            );
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function betAmountPlatformLists(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                $this->dashboard->betAmountPlatformLists(),
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function rechargeWithdrawal(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                $this->dashboard->rechargeWithdrawal(),
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function platformProfitLossPlayerBetting(int $id = 0): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                $this->dashboard->platformProfitLossPlayerBetting($id),
            );
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function platformLists(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                $this->dashboard->platformLists(),
            );
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
