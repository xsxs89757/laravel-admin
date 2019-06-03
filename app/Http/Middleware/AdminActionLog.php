<?php

namespace App\Http\Middleware;

use App\Models\AdminActionLog as AdminActionLogModel;

use Auth;
use Closure;

class AdminActionLog
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
        $response = $next($request);
        $method = $request->method();
        $user = Auth::user();
        $data = [];
        if(method_exists($response,'getData')){
            $data = $response->getData(true);
        }
        if(isset($data['code']) && $method !== 'GET'){
            $admin_action_log = new AdminActionLogModel();
            $admin_action_log->input = serialize($request->input());
            $admin_action_log->path = $request->path();
            $admin_action_log->path_name = \Route::currentRouteName();
            $admin_action_log->ip = $request->getClientIp();
            $admin_action_log->method = $method;
            $admin_action_log->action_uid = $user->id;
            $admin_action_log->action_user = serialize(['userid'=>$user->id,'username'=>$user->username,'nickname'=>$user->nickname]);
            if($data['code'] == 20000){
                $admin_action_log->status = 1;
            }else{
                $admin_action_log->status = 0;
            }
            $admin_action_log->save();
        }
        return $response;
    }
}
