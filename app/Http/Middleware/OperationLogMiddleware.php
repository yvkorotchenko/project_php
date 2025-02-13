<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Laravue\Models\User;
use App\Laravue\Models\OperationLog as OperationLogModel;
use App\Laravue\Models\ActionLog;

class OperationLogMiddleware
{
    private const DEFAULT_ACTION = 'undefined';
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param string $action_en
     * @param string $action_zh
     * @return mixed
     */
    public function handle($request, Closure $next, $actionEN = self::DEFAULT_ACTION, $actionZH = self::DEFAULT_ACTION)
    {
        $param = [
            'name' => 'unknown',
            'action_en' => $actionEN,
            'action_zh' => $actionZH,
            'type_req' => $request->method(),
            'path_req' => $request->path(),
            'data_req' => print_r($request->getContent(), true),
            'ip' => $request->ip(),
        ];

        $actionLog = [
            'user_id' => 0,
            'user_name' => $param['name'],
            'action_en' => $param['action_en'],
            'action_zh' => $param['action_zh'],
        ];

        if (is_null(Auth::user())) {
            if (!is_null($request->input('email'))) {
                $user = User::where('email', $request->input('email'))->first();

                if (!is_null($user)) {
                    $param['name'] = $user->name;

                    $actionLog['user_name'] = $param['name'];
                    $actionLog['user_id'] = $user->id;
                }
            }
        } else {
            $param['name'] = Auth::user()->name;

            $actionLog['user_name'] = $param['name'];
            $actionLog['user_id'] = Auth::id();
        }

        // Operation log
        OperationLogModel::create($param);

        // Action log
        ActionLog::create($actionLog);

        return $next($request);
    }
}
