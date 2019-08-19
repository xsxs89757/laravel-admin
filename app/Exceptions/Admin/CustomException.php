<?php

namespace App\Exceptions\Admin;

use Exception;
use App\Helpers\Admin\AdminApiResponse;

class CustomException extends Exception
{
    use AdminApiResponse;
    /**
     * 将异常渲染至 HTTP 响应值中。
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        $error = static::setError();
        return $this->failed($error[$this->getMessage()],$this->getMessage());
        //return responseJson([],$error[$this->getMessage()],$this->getMessage());
    }

    protected static function setError(){
        return [
            21000 => trans('auth.failed'), //账号密码错误
            21001 => trans('auth.emptyPermissions'), //控权限报错
            21002 => trans('form.addError'), // 添加错误
            21003 => trans('form.editError'), // 编辑错误
            21004 => trans('auth.authError'), //权限错误
            21005 => trans('auth.statusError'), //用户被禁用
            21006 => trans('auth.exceptionLogin'), //异常登录
            21007 => trans('auth.usernameUnique'), //用户名重复
            21008 => trans('auth.menuUnique'), //名称重复,
            21009 => trans('auth.oldPasswordError'),//原始密码不正确
            21010 => trans('auth.configUnique'), //配置名称重复
            21011 => trans('auth.dictionaryUnique') //字典名称重复
        ];
    }
}
