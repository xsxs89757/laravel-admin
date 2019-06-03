<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Transformers\UserTransformer;

class AuthController extends Controller
{
    /**
     * Get a JWT token via given credentials.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $rules = [
            'username'   => [
                'required',
                'min:5',
                'alpha_num'
            ],
            'password' => 'required|string|min:6|max:20',
         ];
        // 验证参数，如果验证失败，则会抛出 ValidationException 的异常
        $params = $this->validate($request, $rules);
        $token = Auth::guard('admin')->attempt($params);
        //使用 Auth 登录用户，如果登录成功，则返回 21000 的 code 和 token，如果登录失败则返回
        if($token){
            $user = Auth::user();
            if($user->status !==1 ){
                Auth::guard('admin')->logout(); //登出
                throw new \App\Exceptions\Admin\CustomException(21005);
            }
            //记录当前登录时间登录ip
            $user->last_login_ip = $request->getClientIp();
            $user->last_login_time = time();
            $user->save();

            return responseJson(['token' => 'bearer ' . $token]);
        }else{
            throw new \App\Exceptions\Admin\CustomException(21000);
        }
    }

    /**
     * 处理用户登出逻辑
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        Auth::guard('admin')->logout();

        return responseJson(['message' => trans('auth.logoutSuccess')]);
    }
}
