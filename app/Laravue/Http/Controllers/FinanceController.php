<?php

namespace App\Laravue\Http\Controllers;

use App\Laravue\Http\Requests\ChangeWithdrawStateRequest;
use App\Laravue\Http\Requests\CreateWithdrawRequest;
use App\Laravue\Services\ResponseFactoryService;
use App\MtSports\Entities\ManualWithdraw;
use App\MtSports\Repositories\FinanceRepository;
use App\MtSports\Repositories\UserInformationRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceController
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private FinanceRepository $financeRepository,
        private UserInformationRepository $userInformationRepository
    ) {
    }

    public function currencyList(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                $this->financeRepository->listAll()
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function userInfo(int $uid): JsonResponse
    {
        try {
            return $this->responseFactory->formattedSTDClassJson(
                $this->userInformationRepository->financeInfo($uid)
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function merchantWithdraw(int $id): JsonResponse
    {
        try {
            return $this->responseFactory->formattedSTDClassJson(
                $this->financeRepository->merchantWithdraw($id)
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getmessage());
        }
    }

    public function createWithdraw(CreateWithdrawRequest $request): JsonResponse
    {
        try {
            return $this->responseFactory->formattedSTDClassJson(
                $this->financeRepository->createWithdraw(
                    new ManualWithdraw(
                        $request->get('uid'),
                        $request->get('currencyId'),
                        $request->get('withdrawAmount'),
                        $request->get('receiveAddress'),
                        Auth::id(),
                        $request->get('notice')
                    )
                )
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getmessage());
        }
    }

    public function withdrawList(Request $request): JsonResponse
    {
        try {
            $result = $this->financeRepository->withdrawList(
                $request->get('page', 1),
                $request->get('size', 10),
                \array_filter($request->except(['page', 'size']), fn ($one) => $one != null)
            );
            return $this->responseFactory->paginatedJson(
                $result->result,
                $result->totalItems,
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function changeWithdrawStatus(ChangeWithdrawStateRequest $request): JsonResponse
    {
        try {
            return $this->responseFactory->formattedSTDClassJson(
                $this->financeRepository->changeWithdrawStatus(
                    $request->get('withdrawId'),
                    $request->get('stateAction')
                )
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function withdrawStatesList(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson($this->financeRepository->withdrawStatesList());
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
