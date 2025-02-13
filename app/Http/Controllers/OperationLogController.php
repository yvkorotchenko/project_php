<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\OperationLogResource;
use App\Laravue\Models\OperationLog;
use App\Laravue\Services\OperationLogService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Laravue\Requests\IndexOperationLogRequest;

class OperationLogController extends BaseController
{
    public function __construct(
        private OperationLogService $operationLogService,
    ) {}
    const ITEM_PER_PAGE = 15;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(IndexOperationLogRequest $request)
    {
//        $filters = [
//            'name' => $request->get('name', ''),
//            'action' => $request->get('action', ''),
//            'created_at' => $request->get('created', ''),
//            'type_req' => $request->get('type', ''),
//            'path_req' => $request->get('path', ''),
//            'data_req' => $request->get('data', ''),
//            'ip' => $request->get('ip', ''),

//            'id' => $request->get('id', ''),
//        ];

        return OperationLogResource::collection(
            $this->operationLogService->list(
                $request->get('limit'),
                $request->validated(),
            )
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Laravue\Models\OperationLog  $operationLog
     * @return \Illuminate\Http\Response
     */
    public function show(OperationLog $operationLog)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Laravue\Models\OperationLog  $operationLog
     * @return \Illuminate\Http\Response
     */
    public function edit(OperationLog $operationLog)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Laravue\Models\OperationLog  $operationLog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OperationLog $operationLog)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Laravue\Models\OperationLog  $operationLog
     * @return \Illuminate\Http\Response
     */
    public function destroy(OperationLog $operationLog)
    {
        //
    }
}
