<?php

namespace App\Laravue\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Laravue\Services\MassMailingListServices;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Models\User;
use Illuminate\Support\Facades\Auth;

class MassMailingListController extends Controller
{

    public function __construct(
        private MassMailingListServices $massmailingListServices,
        private ResponseFactoryService $responseFactory,
    ) {}

    public function create(Request $request): JsonResponse
    {
        $data = $request->all();
        $data['operatorId'] = (int) Auth::user()->id;
        if(!empty($data['operatorId'])){
            $data['operatorName'] = (User::where('id', $data['operatorId'])->first())->name;
        }

        $data["withdrawalStandard"] = $data["withdrawalStandard"] != null ? $data["withdrawalStandard"] : 0;

        try {
            $this->massmailingListServices->create($data);
            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
