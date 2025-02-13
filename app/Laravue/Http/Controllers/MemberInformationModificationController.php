<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Http\Requests\MemberInfoUpdateRemarkRequest;
use Illuminate\Http\Request;
use App\Laravue\Services\MemberInformationModificationService;
use App\Laravue\Requests\MemberInformationModificationUpdateRequest;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;


class MemberInformationModificationController extends Controller
{
    public function __construct(
        private MemberInformationModificationService $memberInformationModificationService,
        private ResponseFactoryService $responseFactory,
    ) {
    }

    public function index(int $id): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                [$this->memberInformationModificationService->index($id)]
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function update(MemberInformationModificationUpdateRequest $request): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                [$this->memberInformationModificationService->update($request->all())]
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    //    public function info(string $fieldName, string $fieldValue): JsonResponse
    public function info(Request $request): JsonResponse
    {
        $fieldName = (string) $request->get('fieldName', "");
        $fieldValue = (string) $request->get('fieldValue', "");

        try {
            return $this->responseFactory->formattedJson(
                [$this->memberInformationModificationService->info($fieldName, $fieldValue)]
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function infoPlayer(string $fieldName, string $fieldValue): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                [$this->memberInformationModificationService->info($fieldName, $fieldValue)]
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }


    public function updateMemberInfo(int $id, Request $request): JsonResponse
    {
        $objMemberInfo = [
            "uid"              => (int) $request->get('id', ""),
            "nickname"         => (string) $request->get('nickname', ""),
            "phone"            => (string) $request->get('phone', ""),
            "email"            => (string) $request->get('email', ""),
            "effectiveBet"     => (int) $request->get('effectiveBet', ""),
            "withdrawStandard" => (int) $request->get('withdrawStandard', ""),
            'countryCode' => $request->get('countryCode', ''),
        ];

        try {

            $this->memberInformationModificationService->updateMemberInfo($objMemberInfo);
            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function updateRemark(MemberInfoUpdateRemarkRequest $request): JsonResponse
    {
        try {
            $this->memberInformationModificationService->updateRemark($request->get('id'), $request->get('remark'));
            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
