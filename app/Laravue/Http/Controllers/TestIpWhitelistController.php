<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Models\User;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\TestIpWhitelistService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;

class TestIpWhitelistController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private Google2FA $google2FA,
        private TestIpWhitelistService $testIpWhitelistService,
    ) {}

    public function verification(Request $request, User $user): JsonResponse
    {
        $ips = $request->get('ips', []);
        $googleCode = $request->get('code', '');

        $this->google2FA->setSecret($user->google_token);

        if ($this->google2FA->verify($googleCode, $this->google2FA->getSecret())) {
            $user->google_code = $googleCode;
            $user->save();

            try {
                $this->testIpWhitelistService->setTestIpWhiteList($ips);

                return $this->responseFactory->messageSuccessJson();
            } catch (\Throwable $e) {
                return $this->responseFactory->error($e->getMessage());
            }
        }

        return $this->responseFactory->error('Incorrect verification code, please try to enter again');
    }
}
