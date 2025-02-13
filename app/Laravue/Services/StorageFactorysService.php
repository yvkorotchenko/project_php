<?php
declare(strict_types=1);

namespace App\Laravue\Services;

use App\Laravue\Services\StaticStorageService;

class StorageFactorysService
{
    public function make(string $token, string $uploadUrl, string $deleteUrl, bool $verify) {
        return new StaticStorageService($token, $uploadUrl, $deleteUrl, $verify);
    }
}