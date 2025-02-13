<?php

namespace App\Http\Middleware;

use App\Laravue\Models\User;
use App\Laravue\Services\ResponseFactoryService;
use Closure;
use Illuminate\Http\Request;

class ValidateUserIPMiddleware
{
    public function __construct(private ResponseFactoryService $responseFactoryService)
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        $userName = $request->get('name', false);
        $user = User::where('name', '=', $userName)->first();
        if (null !== $user) {
            if ($user->ip === '*' || $user->ip === null) {
                return $next($request);
            } elseif ($user->ip !== $request->ip()) {
                return $this->responseFactoryService->error("IP address not allowed");
            }
            return $next($request);
        }
    }
}
