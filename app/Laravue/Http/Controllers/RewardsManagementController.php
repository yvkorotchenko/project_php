<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\RewardsManagementService;
use App\Laravue\Http\Requests\RewardsImageUploadRequest;
use Illuminate\Http\Request;

class RewardsManagementController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private RewardsManagementService $rewardsManagement
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 20);

        try {
            $listPagination = $this->rewardsManagement->listPagination($page, $size);

            return $this->responseFactory->paginatedJson(
                $listPagination->result,
                $listPagination->total,
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $this->rewardsManagement->create($request->all());

            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $this->rewardsManagement->update($id, $request->all());

            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function delete(string $ids, Request $request): JsonResponse
    {
        try {
            $this->rewardsManagement->delete($ids, $request->all());

            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function uploadImage(RewardsImageUploadRequest $request): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson([
                'url' => $this->rewardsManagement->uploadImage($request->file('image')),
            ]);
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
