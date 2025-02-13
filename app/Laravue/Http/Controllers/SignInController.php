<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\MtSports\Repositories\SingInConfigurationRepository;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\Request;

class SignInController extends Controller
{
    public function __construct(
        private SingInConfigurationRepository $singInConfigurationRepository,
        private ResponseFactoryService $responseFactory,
    ) {}

    public function index(): JsonResponse
    {
        return $this->responseFactory->json([
            'data' => $this->singInConfigurationRepository->singin()
        ]);
    }

    public function update(Request $request, int $id)
    {
        $data = $request->get('data', '');

        if ($data !== '') {
            $this->singInConfigurationRepository->updateSingin($data);
        }

        return $this->responseFactory->json([
            'data' => 'update success',
        ]);
    }
}
