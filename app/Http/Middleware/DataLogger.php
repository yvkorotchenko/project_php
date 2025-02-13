<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\OperationLog;

class DataLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }

    public function terminate($request, $response)
    {
        if ( env('API_DATA_LOGGER', true) ) {
            if (env('API_DATA_LOGGER_USE_DB', true) ) {
                $log = new OperationLog();
                $log->user_name = '';
                $log->user_action = '';
                $log->request = $request->method();
//                $log->

                // user_name', 'user_action', 'request', 'request_path', 'request_parameter', 'ip'
            }
        }
    }
}
