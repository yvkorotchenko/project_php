<?php
declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\WinWithdrawMessageService;
use Illuminate\Http\Request;
use Psy\Util\Json;

class WinWithdrawMessageController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactoryService,
        private WinWithdrawMessageService $winWithdrawMessageService,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 20);

        try {
            $listPagination = $this->winWithdrawMessageService->listPagination($page, $size);

            return $this->responseFactoryService->paginatedJson(
                $listPagination->result,
                $listPagination->total,
            );
        } catch (\Throwable $e) {
            return $this->responseFactoryService->error($e->getMessage());
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $this->winWithdrawMessageService->create($request->all());

            return $this->responseFactoryService->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactoryService->error($e->getMessage());
        }
    }

    public function delete(string $ids): JsonResponse
    {
        try {
            $this->winWithdrawMessageService->delete($ids);

            return $this->responseFactoryService->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactoryService->error($e->getMessage());
        }
    }
}
