<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Services\ActionLogsService;
use App\Laravue\Services\ResponseFactoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Closure;

class ActionLogsController extends Controller
{
    public function __construct(
        private ResponseFactoryService $responseFactory,
        private ActionLogsService $actionLogs,
    ) {}

    public function index(Request $request): JsonResponse
    {
        $size = (int) $request->get('size', 20);
        $search = (string) $request->get('search', '{}');
        try {
            $actionLog = $this->actionLogs->listPagination($size, $search)->toArray();

            return $this->responseFactory->formattedJson($actionLog);
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function delete(string $ids): JsonResponse
    {
        try {
            return $this->responseFactory->messageSuccessJson();
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function users(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson($this->actionLogs->allUsers());
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    public function actions(Router $router): JsonResponse
    {
        $routesInfo = collect($router->getRoutes())->map(function ($route) use ($router) {
            return collect($router->gatherRouteMiddleware($route))->map(function ($middleware) {
                if ($middleware instanceof Closure) {
                    return '';
                }

                if (\str_starts_with($middleware, 'App\Http\Middleware\OperationLogMiddleware')) {
                    $result = \explode(':', $middleware);
                    if (count($result) === 2) {
                        return $result[1];
                    }
                }
            })->implode("");
        })->filter()->unique()->all();
        return $this->responseFactory->formattedJson($routesInfo);
    }
}
