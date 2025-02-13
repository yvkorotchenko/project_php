<?php

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\Request;
use App\Laravue\Services\SensetiveOperationLogServices;

class SensetiveOperationLogController extends Controller
{
    public function __construct(
        private SensetiveOperationLogServices $sensetiveOperationLogServices,
        private ResponseFactoryService $responseFactory,
    ) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 20);
        $uid = (int) $request->get('uid', 0);

        try {
            $listPagination = $this->sensetiveOperationLogServices->list($page, $size, $uid);

            return $this->responseFactory->paginatedJson(
                $listPagination->data->result,
                $listPagination->data->totalItems,
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
