<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Services\SevenDaysWelfareService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Http\Requests\SevenDaysWelfareUploadRequest;

class SevenDaysWelfareController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private SevenDaysWelfareService $sevenDaysWelfare
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 20);

        try {
            $listPagination = $this->sevenDaysWelfare->listPagination($page, $size);

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
            $this->sevenDaysWelfare->create($request->all());

            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $this->sevenDaysWelfare->update($id, $request->all());

            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function delete(string $ids, Request $request): JsonResponse
    {
        try {
            $this->sevenDaysWelfare->delete($ids, $request->all());

            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function uploadImage(SevenDaysWelfareUploadRequest $request): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson([
                'url' => $this->sevenDaysWelfare->uploadImage($request->file('image')),
            ]);
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
