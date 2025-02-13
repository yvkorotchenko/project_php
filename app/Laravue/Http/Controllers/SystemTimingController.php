<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Services\SystemTimingService;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SystemTimingController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactoryService,
        private SystemTimingService $systemTimingService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 20);

        try {
            $listPagination = $this->systemTimingService->listPagination($page, $size);

            return $this->responseFactoryService->paginatedJson(
                $listPagination->result,
                $listPagination->total,
            );
        } catch (\Throwable $e) {
            return  $this->responseFactoryService->error($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $this->systemTimingService->create($request->all());

            return $this->responseFactoryService->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactoryService->error($e->getMessage());
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        $systemTiming = $request->all();
        $systemTiming['startTime'] = Str::replace(' ', 'T', $systemTiming['startTime']);
        $systemTiming['endTime'] = Str::replace(' ', 'T', $systemTiming['endTime']);
        try {
            $this->systemTimingService->update($id, $systemTiming);

            return $this->responseFactoryService->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactoryService->error($e->getMessage());
        }
    }

    public function delete(string $ids): JsonResponse
    {
        \Log::info(print_r($ids, true));
        try {
            $this->systemTimingService->delete($ids);

            return $this->responseFactoryService->messageSuccessJson();
        }
        catch (\Throwable $e) {
            return $this->responseFactoryService->error($e->getMessage());
        }
    }
}
