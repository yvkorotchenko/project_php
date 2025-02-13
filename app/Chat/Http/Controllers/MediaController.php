<?php

declare(strict_types=1);

namespace App\Chat\Http\Controllers;

use App\Chat\Http\Requests\UploadMediaRequest;
use App\Chat\Services\StorageService;
use Illuminate\Contracts\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response;
use App\Laravue\Services\StaticStorageService;

class MediaController
{
    public function __construct(
        private ResponseFactory $responseFactory,
        private StorageService $storageService,
        private StaticStorageService $staticStorageService,
    ) {}

    public function uploadMedia(UploadMediaRequest $request): Response
    {
        $file = $request->file('media');

        if (null === $file) {
            return $this->responseFactory->json(
                ['error' => 'File not defined'],
                Response::HTTP_BAD_REQUEST
            );
        }

        $url = $this->staticStorageService->uploadImage($file);

        return $this->responseFactory->json(
            [
                'url' => $url,
                'mime' => $file->getMimeType(),
            ],
            Response::HTTP_CREATED
        );

    }
}
