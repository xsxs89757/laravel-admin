<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
class Language
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
        $lang = $request->header('x-lang');
        if (!empty($lang)) {
            \App::setLocale($lang==='zh'?'zh-CN':$lang);
        }else{
            \App::setLocale(\Config::get('app.locale'));
        }
        return $next($request);
    }
}
