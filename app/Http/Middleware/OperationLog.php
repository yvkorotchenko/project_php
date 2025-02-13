<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Log;
use App\Laravue\Models\User;
use App\Laravue\Models\OperationLog as OperationLogModel;

class OperationLog
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
        $param = [
            'name' => 'unknown',
            'action' => '-t-',
            'type_req' => $request->method(),
            'path_req' => $request->path(),
            'data_req' => print_r($request->getContent(), true),
            'ip' => $request->ip(),
        ];

        // 'created_at' => date('Y-m-d H:m:s'),

        if (is_null(Auth::user())) {
            if (!is_null($request->input('email'))) {
                $user = User::where('email', $request->input('email'))->first();

                if (!is_null($user)) {
                    $param['name'] = $user->name;
                }
            }
        } else {
            $param['name'] = Auth::user()->name;
        }

        OperationLogModel::create($param);

        return $next($request);
    }
}
