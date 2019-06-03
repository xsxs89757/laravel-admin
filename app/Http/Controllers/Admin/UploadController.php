<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    /**
     * 单图上传
     */
    protected function signleImage(Request $request)
    {
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
}
