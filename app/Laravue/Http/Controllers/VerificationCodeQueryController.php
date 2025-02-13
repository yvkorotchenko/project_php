<?php

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\VerificationCodeQueryServices;

class VerificationCodeQueryController extends Controller
{
    public function __construct(
        private VerificationCodeQueryServices $verificationCodeQuery,
        private ResponseFactoryService $responseFactory,
    ) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $result = $this->verificationCodeQuery->getVerificationCode($request->all());
            $answer = (!empty($result->data))?
                ['error' => '','message' => 'Verification code received','code' => $result->data]:
                ['error' => $result->message,'message' => $result->message, 'code' => ''];
            return json_encode($answer);
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
