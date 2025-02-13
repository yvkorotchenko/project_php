<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Contracts\Routing\ResponseFactory;
use PragmaRX\Google2FA\Google2FA;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Google2faAuthenticationVerificationController extends Controller
{
    private const DEFAULT_CHARSET = 'UTF-8';
    private const DEFAULT_LENGTH_SECRET_KEY = 64;
    private const DEFAULT_PREFIX = 'mt-sport';
    private $company_name;
    private $company_email;
    private const QR_CODE_SIZE = 300;
    private const SITE_URL = 'bi.mt666.at';

    public function __construct(
        private Google2FA $google2fa,
        private ResponseFactoryService $responseFactory,
    )
    {
        $this->google2fa->setSecret($this->google2fa->generateSecretKey(self::DEFAULT_LENGTH_SECRET_KEY));
    }

    public function getSecretKey (): string
    {
        return $this->google2fa->getSecret();
    }

    public function setCurrentUser(string $user): void
    {
        $this->company_name = $user;
    }

    public function setCurrentIp(string $ip): void
    {
        $this->company_email = $ip;
    }

    public function resetSecretKey(string $secret): void
    {
        $this->google2fa->setSecret($secret);
    }

    public function initQrCodeLabels(User $user)
    {
        $this->setCurrentUser($user->name . ' ' . $user->nick);
        $this->setCurrentIp($user->ip);
    }

    public function setQrCodeLabels(string $label, User $user) {
        $this->setCurrentUser($label);
        $this->setCurrentIp($user->name);
    }

    public function getQrCodeForUser(User $user, Request $request, string $secret = null): JsonResponse
    {
        try {
            $this->setQrCodeLabels(self::SITE_URL, $user);
            $this->resetSecretKey($user->google_token);

            return $this->responseFactory->text(
                (string) QrCode::encoding(self::DEFAULT_CHARSET)
                    ->size(self::QR_CODE_SIZE)
                    ->generate(
                        $this->google2fa->getQRCodeUrl(
                            $this->company_name,
                            $this->company_email,
                            is_null($secret) ? $this->google2fa->getSecret() : $secret,
                    ),
                ),
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
