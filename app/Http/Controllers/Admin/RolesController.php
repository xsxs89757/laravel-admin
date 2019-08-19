<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Roles;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\Admin\Roles as RolesResources;

class RolesController extends AdminApiController
{
    /**
     * 获取角色权限列表
     */
    protected function list(Request $request)
    {
    	$limit = $request->input('limit',20);
        $name = $request->input('name');
    	$userid = Auth::id();
    	$list = Roles::with(['adminUsers','permissions'])->when($userid !==1 ,function($query) use($userid){
    		$query->where('create_uid',$userid);
    	})->when(!empty($name),function($query)use($name){
            $query->where('name',$name);
        })->paginate($limit);
    	$list = RolesResources::collection($list);
    	return $this->success($list->resource->toArray());
    }

    /**
     * 添加角色
     */
    
    protected function add(Request $request)
    {
    	$rules = [
            'name'   => 'required',
            'checkedKeys' => 'required'
        ];
        $message = [
            'checkedKeys.required'=>trans('form.checkKeysRequired')
        ];
        $this->validate($request, $rules,$message); //验证输入
    	$attributes = ['name'=>$request->input('name'),'guard_name'=>$request->input('guard_name'),'create_uid'=>Auth::id()];
    	$permissions = $request->input('checkedKeys');
    	$permissions = array_map('urlHaddleKey',$permissions);
    	$role = Roles::createAndPermission($attributes,$permissions);
        if($role){
            $detail = Roles::with(['adminUsers','permissions'])->find($role->id);
            $detail = new RolesResources($detail);
            return $this->success($detail);
        }else{
            throw new \App\Exceptions\Admin\CustomException(21002);
        }
    	
    }

    /**
     * 编辑角色 
     */
    protected function edit(Request $request)
    {
    	$rules = [
    		'id' => 'required|numeric',
            'name' => 'required',
            'checkedKeys' => 'required'
         ];
        $message = [
            'checkedKeys.required'=>trans('form.checkKeysRequired')
        ];
        $this->validate($request, $rules,$message);
        $attributes = ['id'=>$request->input('id'),'name'=>$request->input('name'),'guard_name'=>$request->input('guard_name')];
        $permissions = $request->input('checkedKeys');
        $permissions = array_map('urlHaddleKey',$permissions);
        $role = Roles::saveById($attributes,$permissions);
        if($role){
            return $this->message(trans('form.editSuccess'));
        }else{
            throw new \App\Exceptions\Admin\CustomException(21003);
        }
    }

    /**
     * 删除角色
     */
    protected function delete($id)
    {
        $uid = Auth::id();
        if($uid === 1){ //超管
            Roles::destroy($id);
        }else{
            $roles = Roles::find($id);
            if($roles->create_uid === $uid){
                Roles::destroy($id);
            }else{
                throw new \App\Exceptions\Admin\CustomException(21004);
            }
        }
        return $this->message('');
    }
}
