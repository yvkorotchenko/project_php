<?php

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\VipRechargeServices;
use Illuminate\Support\Facades\Auth;

class VipRechargeController extends Controller
{
    public function __construct(
        private VipRechargeServices $vipRecharge,
        private ResponseFactoryService $responseFactory,
    ) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = [
            "uid" => $request->playerID,
            "amount" => $request->amount,
            "operatorId" => Auth::user()->id,
        ];
        try {
            $result = $this->vipRecharge->createRecharge($data);
            $answer = (!empty($result->data))?
                ['error' => '','message' => 'Recharge sent for confirmation']:
                ['error' => 'Error, Recharge not sent'];
            return json_encode($answer);
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $query = $request->all();
        try {
            $listPagination = $this->vipRecharge->listRechargeHistory($query);

            return $this->responseFactory->paginatedJson(
                $listPagination->data->result,
                $listPagination->total,
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
    public function statuses()
    {
        try {
            $listStatuses = $this->vipRecharge->listRechargeStatuses();
            $answer = (!empty($listStatuses->data))?
                ['error' => '','message' => 'VIP Recharge statuses done', 'data' => $listStatuses->data]:
                ['error' => 'VIP Recharge statuses not done'];
            return json_encode($answer);
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
