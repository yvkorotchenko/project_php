<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Services\BannerService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Http\Requests\BannerImageUploadRequest;

class BannerController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private BannerService $bannerService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $size = (int) $request->get('size', 20);
        $page = (int) $request->get('page', 1);

        try {
            $listPagination = $this->bannerService->listPagination($page, $size);

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
            $this->bannerService->create($request->all());

            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $this->bannerService->update($id, $request->all());

            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function delete(string $ids, Request $request): JsonResponse
    {
        try {
            $this->bannerService->delete($ids, $request->all());

            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function uploadImage(BannerImageUploadRequest $request): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson([
                'url' => $this->bannerService->uploadImage($request->file('image')),
            ]);
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
