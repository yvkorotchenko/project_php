<?php

namespace App\Chat\Services;

use App\Chat\Models\ChatAnonymousClient;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class AnonymousChatUserService
{
    private string $secretPhrase;
    private string $privateKeyFullPath;
    private string $privateKeyPassword;

    public function __construct(
        string $privateKeyRelativePath,
        string $privateKeyPassword,
        string $secretPhrase
    ) {
        $this->privateKeyFullPath = 'file://' . base_path() . '/' . $privateKeyRelativePath;
        $this->privateKeyPassword = $privateKeyPassword;
        $this->secretPhrase = $secretPhrase;
    }

    public function validPublicKey(string $publicKeyString): bool
    {
        if ('' === $publicKeyString) {
            return false;
        }

        try {
            $publicKeyString = 
                             '-----BEGIN PUBLIC KEY-----'
                             . PHP_EOL
                             . $publicKeyString
                             . PHP_EOL
                             . '-----END PUBLIC KEY-----';
            $publicKey = openssl_pkey_get_public($publicKeyString);
            $privateKey = openssl_pkey_get_private($this->privateKeyFullPath, $this->privateKeyPassword);
            openssl_public_encrypt($this->secretPhrase, $encryptedData, $publicKey);
            openssl_private_decrypt($encryptedData, $decryptedData, $privateKey);

            return $this->secretPhrase === $decryptedData;
        } catch (\Throwable $e) {
            return false;
        }
    }

    public function generateTemporayToken(): string
    {
        return hash('sha256', Str::random(60));
    }

    public function createAnonymousChatClient(string $token): ChatAnonymousClient
    {
        $chatClient = new ChatAnonymousClient;
        $chatClient->token = $token;
        $chatClient->save();

        return $chatClient;
    }

    public function validToken(string $token): bool
    {
        $clientAnon = $this->findClientByToken($token);

        return $clientAnon !== null
            && $clientAnon->created_at->lt(Carbon::now()->addMinutes(10));
    }

    public function findClientByToken(string $token): ?ChatAnonymousClient
    {
        return ChatAnonymousClient::where(['token'=> $token])->first();
    }

    public function exists(int $id): bool
    {
        return ChatAnonymousClient::whereId($id)->exists();
    }
}
