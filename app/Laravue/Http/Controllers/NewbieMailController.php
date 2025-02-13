<?php

namespace App\Laravue\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Laravue\Services\NewbieMailServices;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Models\User;

class NewbieMailController extends Controller
{
    
    public function __construct(
        private NewbieMailServices $newbieMailServices,
        private ResponseFactoryService $responseFactory,
    ) {}

    public function store(Request $request): JsonResponse
    {
        $operatorId = (int) $request->get('operatorId', 0);
        $operatorName = (User::where('id', $operatorId)->first())->name;
        $data = [
            'contentEn'    => (string) $request->get('contentEn', ""),
            'contentLocal' => (string) $request->get('contentLocal', ""),
            'state'        => (int) $request->get('created', 1),
            'titleEn'      => (string) $request->get('titleEn', ""),
            'titleLocal'   => (string) $request->get('titleLocal', ""),
            'operatorId'   => $operatorId,
            'operatorName' => $operatorName,
        ];

        try {
            
            $this->newbieMailServices->store($data);
            return $this->responseFactory->messageSuccessJson();

        } catch (\Throwable $e) {

            return $this->responseFactory->error($e->getMessage());

        }
    }

}
