<?php

namespace App\Laravue\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Services\OperationLogService;
use App\Laravue\Recources\OperationLogResource;
use Illuminate\Http\Request;
use Illuminate\Routing\ResponseFactory;
use Illuminate\Http\JsonResponse;

class OperationLogController extends Controller
{
    public function __construct(
        private OperationLogService $operationLogService,
        private ResponseFactory $responseFactory,
    ) {}
    public function index(Request $request): JsonResponse
    {
        return OperationLogResource::collection(
            $this->operationLogService->list($request->all())
        );
    }
}
