<?php

namespace App\Laravue\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Laravue\Services\MailingListServices;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\Auth;

class MailingListController extends Controller
{

    public function __construct(
        private MailingListServices $mailingListServices,
        private ResponseFactoryService $responseFactory,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $data = $request->all();
        // $data['operatorId'] = (int) Auth::user()->id;
        if(!empty($data['operatorId'])){
            $data['operatorName'] = (User::where('id', $data['operatorId'])->first())->name;
        }
        $listPagination = $this->mailingListServices->list($data);
        try {
            return $this->responseFactory->paginatedJson(
                $listPagination->data->result,
                $listPagination->data->totalItems,
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function destroy(int $id, Request $request): JsonResponse
    {

        $data = $request->all();
        if (empty($data[0]['messageId'])) {
            $data = [
                $data
            ];
        }

//        dd($data);
        try {
            $this->mailingListServices->destroy($data);
            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function create(Request $request): JsonResponse
    {

        $data = $request->all();
        $data['operatorId'] = (int) Auth::user()->id;
        if(!empty($data['operatorId'])){
            $data['operatorName'] = (User::where('id', $data['operatorId'])->first())->name;
        }
        $data['uids'] = explode(',', $data['uids']);
        try {
            $this->mailingListServices->create($data);
            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }

    }
}
