<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function __construct(){
        //上传权限检测  暂时定义  [update]  需要修改为上传方法与路由挂接
        $this->middleware('auth.upload');
    }
    /**
     * 单图上传
     */
    protected function signleImage(Request $request)
    {
        /**
         * 定义可使用该上传的路由
         * @var 示例方式
         */
        $access = ['adminUsers.list.addAdminUser','adminUsers.list.editAdminUser'];
        $auth = $request->input('auth');
        if(!in_array($auth,$access)){
            throw new \App\Exceptions\Admin\CustomException(21006);
        }
    	/**
    	 * [update] 未填写比例检测  未填写缩略图生成
    	 */
    	$this->validate($request,[
    		'file'=>'image|max:1024*1024*2'
    	],
    	[
    		'file.image'=>trans('form.upload.imgUploadTypeError'),
    		'file.max'=>trans('form.upload.imgUploadSizeError',['size'=>2])
    	]);
    	$type = $request->input('type','image');
    	$store = $type.'/'.date('Ymd');
		$path = $request->file('file')->store($store);
		return responseJson(['real_url'=>Storage::url($path),'path'=>$path]);
    }

    /**
     * 配置上传
     */

    protected function upConfig(Request $request)
    {
        $access = ['system.config'];
        $auth = $request->input('auth');
        if(!in_array($auth,$access)){
            throw new \App\Exceptions\Admin\CustomException(21006);
        }
        $store = 'system';
        $path = $request->file('file')->store($store);
        return responseJson(['real_url'=>Storage::url($path),'path'=>$path]);

    }
}
