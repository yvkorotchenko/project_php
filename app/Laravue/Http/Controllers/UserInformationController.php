<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\UserInformationService;

class UserInformationController extends Controller
{
    public function __construct(
        private UserInformationService $userInformationService,
        private ResponseFactoryService $responseFactory,
    ) {}

    public function countNewMembers(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                $this->userInformationService->newUsersCount(),
            );
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function userCountRegistration(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                $this->userInformationService->userCountRegistration(),
            );
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function userCountLogin(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson(
                $this->userInformationService->userCountLogin(),
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
