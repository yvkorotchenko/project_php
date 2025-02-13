<?php

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\ClientIPManagementServices;

class ClientIPManagementController extends Controller
{
    public function __construct(
        private ClientIPManagementServices $clientIPManagementServices,
        private ResponseFactoryService $responseFactory,
    ) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = (int) $request->page ?? 1;
        $size = (int) $request->size ?? 15;
        try {
            $listPagination = $this->clientIPManagementServices->list($page, $size);
            
            return $this->responseFactory->paginatedJson(
                $listPagination->data->result,
                $listPagination->data->totalItems,
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $id = (int) $request->id;
        $state = (bool) $request->state;
        
        try {
            $this->clientIPManagementServices->update($id, $state);
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
