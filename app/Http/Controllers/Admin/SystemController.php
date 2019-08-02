<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\Config;
use Auth;
use DB;

use App\Http\Resources\Admin\Config as ConfigResources;

class SystemController extends Controller
{
	/**
	 * 分组配置项
	 * @Author   lei.wang
	 * @DateTime 2019-06-17T15:18:00+0800
	 * @return   json
	 */
    protected function mapsOptions()
    {
    	$type = config('my.admin_type');
    	$group = config('my.admin_group');
    	$mapsOptions = [];
    	$mapsOptions[] = [
			"label"=>"全部",
			"key"=>"all"
    	];
    	foreach($group as $key=>$value){
    		$mapsOptions [] = [
    			"label"=>$value,
    			"key"=>$key
    		];
    	}
    	$mapsOptionsType = [];
    	foreach($type as $key=>$value){
    		$mapsOptionsType [] = [
    			"label"=>$value,
    			"key"=>$key
    		];
    	}
    	return responseJson(['group'=>$mapsOptions,'type'=>$mapsOptionsType]);
    }

    /**
     * 获取设置下面拥有内容的分组项
     * @Author   lei.wang 
     * @DateTime 2019-07-15T18:06:09+0800
     * @return   json
     */
    protected function mapsGroup()
    {
        $group = config('my.admin_group');
        $mapsGroup = [];
        $count = Config::selectRaw('count(id) as c,`group`')->groupBy('group')->pluck('c','group');
        foreach($group as $key=>$value){
            if(isset($count[$key]) && $count[$key]>0){
                $mapsGroup [] = [
                    "label"=>$value,
                    "key"=>$key
                ];
            }
            
        }
        return responseJson($mapsGroup);
    }

    /**
     * 配置列表 
     * @Author   lei.wang
     * @DateTime 2019-06-17T16:20:50+0800
     * @param    Request                  $request
     * @return   Json
     */
    protected function list(Request $request)
    {
    	$limit = $request->input('limit',20);
    	$group = config('my.admin_group');
    	$keys = array_keys($group);
    	array_push($keys,'all');
    	$rules = [
            'type'   => ['required',Rule::in($keys)]
        ];
        $this->validate($request, $rules); //验证输入
    	$type = $request->input('type');
        $keyword = $request->input('keyword');
    	$list = Config::when($type!=='all',function($query) use($type){
    		$query->where('group',$type);
    	})->when($keyword!=='',function($query) use($keyword){
            $query->where(function ($query) use($keyword){
                $query->where('name','like','%'.$keyword.'%')->orWhere('title','like','%'.$keyword.'%');
            });
        })->orderBy('sort','asc')->orderBy('created_at','desc')->paginate($limit);
    	$list = ConfigResources::collection($list);
    	return responseJson($list->resource->toArray());
    }

    /**
     * 根据分组调取配置
     * @Author   lei.wang
     * @DateTime 2019-07-29T15:07:14+0800
     * @param    Request                  $request
     * @return   Json
     */
    protected function config(Request $request)
    {
        $group = config('my.admin_group');
        $keys = array_keys($group);
        $rules = [
            'type'   => ['required',Rule::in($keys)]
        ];
        $this->validate($request, $rules); //验证输入
        $type = $request->input('type');
        $list = Config::when($type,function($query) use($type){
            $query->where('group',$type);
        })->orderBy('sort','asc')->orderBy('created_at','desc')->get();
        $list = ConfigResources::collection($list);
        return responseJson($list);
    }

    /**
     * 校检输入参数
     * @Author   lei.wang
     * @DateTime 2019-06-18T18:06:00+0800
     * @param    [type]                   $request
     * @param    string                   add|edit
     * @return   array       			attributes           
     */
    private function validateRequest($request,$t = 'add')
    {
    	$type = config('my.admin_type');
    	$group = config('my.admin_group');
    	$keysType = array_keys($type);
    	$keysGroup = array_keys($group);
    	$rules = [
    		'name'	=> 'required',
    		'title'	=> 'required',
            'type'  => ['required',Rule::in($keysType)],
            'group' => ['required',Rule::in($keysGroup)]
        ];
        if(in_array($type,['select','redio','checkbox'])){
            $rules['extra'] = 'required';
        }
    	if($t === 'edit'){
    		$rules['id'] = 'required';
    	}
    	$this->validate($request, $rules); //验证输入
    	$attributes = [
    		'name'=>$request->input('name'),
    		'title'=>$request->input('title'),
    		'type'=>$request->input('type'),
    		'group'=>$request->input('group'),
            'extra'=>$request->input('extra'),
    		'remark'=>$request->input('remark'),
    		'status'=>$request->input('status')?1:0,
    		'store'=>$request->input('store')?1:0,
    		'sort'=>$request->input('sort')
    	];
    	if($t === 'edit'){
    		$attributes['id'] = $request->input('id');
    	}
    	return $attributes;
    }
	/**
     * 添加配置项
     * @Author   lei.wang
     * @DateTime 2019-06-17T16:26:52+0800
     * @param    Request                  $request
     * @return   Json                   
     */
    protected function add(Request $request)
    {
    	$attributes = $this->validateRequest($request);
    	$config = Config::createById($attributes);
    	if($config){
			return responseJson($config);
    	}else{
    		throw new \App\Exceptions\Admin\CustomException(21002);
    	}
    	
    }
    /**
     * 编辑配置项
     * @Author   lei.wang
     * @DateTime 2019-06-17T16:26:52+0800
     * @param    Request                  $request
     * @return   Json                   
     */
    protected function edit(Request $request)
    {
    	$attributes = $this->validateRequest($request,'edit');
    	$config = Config::saveById($attributes);
    	if($config){
            return responseJson(['message'=>trans('form.editSuccess')]);
        }else{
            throw new \App\Exceptions\Admin\CustomException(21003);
        }
    }
    /**
     * 删除配置项
     * @Author   lei.wang
     * @DateTime 2019-06-17T16:24:07+0800
     * @param    Request                  $request [description]
     * @return   [type]                            [description]
     */
    protected function delete($id)
    {
        $uid = Auth::id();
        if($uid === 1){ //超管
            Config::destroy($id);
        }
        return responseJson([]);
    }
    /**
     * 更新排序
     * @Author   lei.wang
     * @DateTime 2019-06-17T16:23:44+0800
     * @param    Request                  $request
     * @return   Json                           
     */
    protected function sort(Request $request)
    {
        $input = $request->input();
        if($input){
            $tmp = [];
            foreach ($input as $key => $value) {
                $tmp[] =  collect($value)->toArray();
            }
            $updateCount = Config::updateBatch('config',$tmp);
            Config::refreshCache();
            return responseJson(['update_count'=>$updateCount]);
        }
    }
    /**
     * 更新配置内容
     * @Author   lei.wang
     * @DateTime 2019-06-17T16:23:03+0800
     * @param    Request                  $request 
     * @return   Json
     */
    protected function batch(Request $request)
    {
        $input = $request->input();
        $tmp = [];
        foreach($input as $key=>$value){
            if($value){
                $tmp[] = ['name'=>$key,'value'=>is_array($value)?serialize($value):$value];
            }
        }
        $updateCount = Config::updateBatch('config',$tmp);
        Config::refreshCache();
        return responseJson(['update_count'=>$updateCount]);
    }


}
