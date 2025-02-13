<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\ImportantNoticeService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ImportantNoticeController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private ImportantNoticeService $importantNotice,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 20);

        try {
            $listPagination = $this->importantNotice->listPagination($page, $size);

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
            $this->importantNotice->create($request->all());

            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function update(int $id, Request $request): JsonResponse
    {
        try {
            $this->importantNotice->update($id, $request->all());

            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return  $this->responseFactory->error($e->getMessage());
        }
    }

    public function delete(Request $request): JsonResponse
    {
        $ids = $request->get('ids');
        try {
            $this->importantNotice->delete($ids);

            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
