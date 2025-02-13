<?php

namespace App\Laravue\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Laravue\Services\UserLoginLogServices;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;

class UserLoginLogController extends Controller
{
    public function __construct(
        private UserLoginLogServices $userLoginLog,
        private ResponseFactoryService $responseFactory,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 20);
        $uid = (int) $request->get('uid', 0);

        $listPagination = $this->userLoginLog->listPagination($page, $size, $uid);

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
