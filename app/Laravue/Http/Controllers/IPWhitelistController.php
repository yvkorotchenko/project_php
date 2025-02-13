<?php

namespace App\Laravue\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Laravue\Services\IPWhitelistServices;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class IPWhitelistController extends Controller
{
    public function __construct(
        private IPWhitelistServices $iPWhitelistServices,
        private ResponseFactoryService $responseFactory,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 20);

        $listPagination = $this->iPWhitelistServices->listPagination($page, $size);

        try {
            return $this->responseFactory->paginatedJson(
                $listPagination->result,
                $listPagination->totalItems,
            );
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $data = [
            "ipAddress"  => $request->ipAddress,
            "operatorId" => Auth::user()->id,
            "remark"     => $request->remark
        ];
        try {
            $this->iPWhitelistServices->store($data);
        } catch(\Throwable $e) {
            // return $this->responseFactory->error('This IP is added');
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->iPWhitelistServices->destroy($id);
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
