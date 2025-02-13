<?php

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\AllPlayersQueryServices;

class AllPlayersQueryController extends Controller
{
    
    public function __construct(
        private AllPlayersQueryServices $allPlayersQuery,
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
        $query = $request->all();
        try {
            $listPagination = $this->allPlayersQuery->list($query);

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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function channel()
    {
        try {
            return $this->responseFactory->formattedJson(
                ['data' => $this->allPlayersQuery->channel()]
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function vipLevel()
    {
        try {
            return $this->responseFactory->formattedJson(
                ['data' => $this->allPlayersQuery->vipLevel()]
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
