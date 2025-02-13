<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Services\AvatarService;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Laravue\Http\Requests\AvatarImageUploadRequest;
use App\Laravue\Http\Requests\AvatarCreateRequest;

class AvatarsController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private AvatarService $avatar,
    ) {}
    public function index(Request $request) {
        $size = (int) $request->get('size', 20);

        try {
            return $this->responseFactory->formattedJson(
                $this->avatar->listPagination($size),
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function store(AvatarCreateRequest $request): JsonResponse
    {
        try {
            $this->avatar->create($request->all());
            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function update(int $id, Request $request)
    {
        try {
            $this->avatar->update($id, $request->all());
            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function delete(string $ids, Request $request) {
        $urls = $request->get('urls', []);

        try {
            return $this->responseFactory->formattedJson(
                $this->avatar->delete($ids, $urls),
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function uploadImages(AvatarImageUploadRequest $request)
    {
        try {
            return $this->responseFactory->formattedJson([
                'url' => $this->avatar->uploadImage($request->file('image')),
            ]);
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
