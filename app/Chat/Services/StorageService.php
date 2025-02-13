<?php

declare(strict_types=1);

namespace App\Chat\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class StorageService
{
    private const IMAGE_STORE_NAME = 'public';
    private const RANDOM_FILE_NAME_LENGTH = 16;
    private const BASE_DIR = 'chat';

    /**
     * Save file in public storage and returns url to saved file
     */
    public function saveUploadedFile(UploadedFile $file): string
    {
        if (!$file->isValid()) {
            throw new \Exception('Uploaded file is invalid');
        }

        $fileName = $this->generateFileName($file->extension());
        $file->storeAs(self::IMAGE_STORE_NAME, $fileName);

        return url(Storage::url($fileName));
    }

    /**
     * Converts url file path to localStorage file path
     */
    public function getLocalFilePathByUrl(string $fileUrl): string
    {
        $storage = Storage::disk(self::IMAGE_STORE_NAME);
        // TODO: change hardcoded /storage to get from $storage
        $urlPices = \explode(URL::to('/storage') . '/', $fileUrl);
        if (!\array_key_exists(1, $urlPices)) {
            return '';
        }

        $filePath = $urlPices[1];

        if (!$storage->exists($filePath)) {
            return '';
        }

        return $filePath;
    }

    /**
     * Returns mime-type of file by given file path
     */
    public function getFileExtension(string $filePath): string
    {
        $result = File::mimeType($this->getFullFilePath($filePath));

        return $result === false ? '' : $result;
    }

    // TODO: move this peaceofsheet to autowire
    private function getFullFilePath(string $filePath): string
    {
        return Storage::disk(self::IMAGE_STORE_NAME)
            ->getDriver()
            ->getAdapter()
            ->getPathPrefix() . $filePath;
    }

    private function generateFileName(string $fileExtension): string
    {
        return
            self::BASE_DIR
            . DIRECTORY_SEPARATOR
            . $this->generateDirectoryName()
            . DIRECTORY_SEPARATOR
            . Str::random(self::RANDOM_FILE_NAME_LENGTH)
            . '.'
            . $fileExtension;
    }

    private function generateDirectoryName(): string
    {
        return (string)date('Y-m-d');
    }
}
