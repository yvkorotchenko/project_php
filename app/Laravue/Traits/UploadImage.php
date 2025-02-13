<?php
declare(strict_types=1);

namespace App\Laravue\Traits;

use Illuminate\Http\UploadedFile;

trait UploadImage
{
    public function uploadImage(UploadedFile $file): string
    {
        $types = ['gif', 'jpeg', 'jpg', 'png', 'svg', 'svg+xml'];

        if (!in_array($file->clientExtension(), $types)) {
            throw new \LogicException('Invalid field type');
        }

        return $this->staticStorageService->uploadImage($file);
    }
}