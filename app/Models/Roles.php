<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use App\Models\AdminMenu;
use Illuminate\Support\Facades\Auth;

class Roles extends Role
{
    /**
     * 绑定后台会员表
     */

    public function adminUsers()
    {
    	return $this->belongsTo('App\Models\AdminUsers','create_uid');
    }

    public static function createAndPermission(array $attributes = [],array $permissions = [])
    {
        $static = new static();
        if(!$static::permissionsInUsers($permissions)){//检测 传递的权限是否在当前登录用户的权限中
            throw new \App\Exceptions\Admin\CustomException(21006);
        }
    	//给该账户授权
    	$role = DB::transaction(function () use($static,$attributes,$permissions) {
		    $role = $static::create($attributes);
		    $role->givePermissionTo($permissions); //授权

		    return $role;
		});
		return $role;
    } 

    public static function saveById(array $attributes = [],array $permissions = [])
    {
        $static = new static();
        if(!$static::permissionsInUsers($permissions)){//检测 传递的权限是否在当前登录用户的权限中
            throw new \App\Exceptions\Admin\CustomException(21006);
        }
    	
        $uid = Auth::id();
        $role = DB::transaction(function () use($static,$attributes,$permissions,$uid) {
            $id = $attributes['id'];
            unset($attributes['id']);
            $roleCollect = $static::find($id);
            if($roleCollect->create_uid === $uid || $uid === 1){ //创建者与主账号均有权利修改
                $role = $static::where('id',$id)->update($attributes);
                $roleCollect->syncPermissions($permissions); //撤销并刷新权限
                AdminMenu::refreshCacheRoleMenu(); //刷新权限缓存
                return $roleCollect;
            }else{
                throw new \App\Exceptions\Admin\CustomException(21004);
                
            }
        });
        
        return $role;
    }

    /**
     * 获取当前用户所有权限数组
     */
    public static function getUsersPermissions()
    {
        $user = Auth::user();
        $permissions = $user->id===1?Permission::all()->where('guard_name','admin'):$user->getAllPermissions();
        $permissions = collectToFieldArray($permissions,'name');
        return $permissions;
    }

    /**
     * 检测权限数组是否在登录用户的权限中
     */
    public static function permissionsInUsers(array $permissions = [])
    {
        $permissionsAll =  static::getUsersPermissions();
        $flag = true;
        foreach ($permissions as $key => $value) {
            if(in_array($value,$permissionsAll)){
                continue;
            }else{
                $flag = false;
                break;
            }
        }
        return $flag;
    }


    /**
     * 获取当前登录用户的可授权的权限组与创建的权限组
     */
    
    public static function getUsersRoles()
    {
        $user = Auth::user();
        $roles1 = static::when($user->id !==1 ,function($query) use($user){
            $query->where('create_uid',$user->id);
        })->pluck('name')->toArray();
        $roles2 = $user->getRoleNames()->toArray();
        $roles = array_unique(array_merge($roles1,$roles2));
        return $roles;
    }

    /**
     * 检测角色数组是否在登录用户的角色中
     */
    public static function rolesInUsers(array $roles = [])
    {
        $rolesAll = static::getUsersRoles();
        $flag = true;
        foreach ($roles as $key => $value) {
            if(in_array($value,$rolesAll)){
                continue;
            }else{
                $flag = false;
                break;
            }
        }
        return $flag;
    }
    
}
