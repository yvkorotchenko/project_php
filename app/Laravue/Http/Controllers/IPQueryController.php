<?php

namespace App\Laravue\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Laravue\Services\IPQueryServices;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;

class IPQueryController extends Controller
{
    public function __construct(
        private IPQueryServices $iPQueryServices,
        private ResponseFactoryService $responseFactory,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 20);
        $ip = (string) $request->get('ip', 20);

        $listPagination = $this->iPQueryServices->listPagination($page, $size, $ip);

        try {
            return $this->responseFactory->paginatedJson(
                $listPagination->result,
                $listPagination->totalItems,
            );
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
