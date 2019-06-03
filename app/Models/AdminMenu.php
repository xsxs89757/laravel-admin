<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminMenu extends BaseModel
{
   
	/**
	* 限制查询whereIn key的 所有权限按钮
	*/

	public function scopeOfKey($query,$keyArr)
	{
			return $query->whereIn('key',$keyArr);
	}

	/**
	* 获取当前登录用户的菜单与按钮列表
	* @public
	*/
	public static function getRoleMenu()
	{
		$user = Auth::user();
		//获取当前域名下的所有按钮权限
		$permissions = $user->id===1?Permission::all()->where('guard_name',app_id()):$user->getAllPermissions();
		$permissions = collectToFieldArray($permissions,'name');
		$roles = $user->getRoleNames()->toArray();
		$minutes = config('permission.cache.expiration_time', config('permission.cache_expiration_time'));
		$menu = Cache::tags('roleMenu')->remember('menu'.implode($roles),$minutes, function () use($permissions) {
	   		return static::OfKey($permissions)->orderBy('sort','asc')->orderBy('id','asc')->get();
		});
		$roleMenu = static::getRoleMenuAndHaddle($menu);
		
		return $roleMenu;
	}

	/**
	* 获取当前权限并缓存并处理
	* @protected
	*/
	protected static function getRoleMenuAndHaddle($menu)
	{
		$newMenu = [];
		foreach($menu as $key=>$value){
			if($value->pid === 0){
				$value->children = static::getChildRoleMenuHaddle($menu,$value->id);
				$newMenu[] = $value;
			}
		}
		return $newMenu;	
	}

	/**
	 * 获取参数 获取权限menu
	 * @$type=1时 $permissions为集合
	 * @$type=2时 $permissions为已经取出的name
	 */
	public static function getRoleMenuParam($permissions,$type=1)
	{

		$permissions = $type == 1?collectToFieldArray($permissions,'name'):$permissions;
		$menu = collect(static::getAllMenu()->get('id'))->whereIn('key',$permissions);
		$roleMenu = static::getRoleMenuAndHaddle($menu);
		return $roleMenu;
	}

	/**
	 * 递归处理children
	 */

	protected static function getChildRoleMenuHaddle($menu,$pid)
	{
		$newMenu = [];
		foreach($menu as $key=>$value){
			if($value->pid === $pid){
				$value->children = static::getChildRoleMenuHaddle($menu,$value->id);
				$newMenu[] = $value;
			}
		}
		return $newMenu;
	}

	/**
	* 刷新按钮与权限缓存
	*/
	public static function refreshCachePermissionRoleMenu()
	{
			//刷新permission缓存
			app()['cache']->forget(config('permission.cache.key'));
			Cache::forget('menulist');
			//刷新所有权限
			Cache::tags('roleMenu')->flush();
			return ;
			
	}

	/**
	* 刷新单独按钮缓存
	*/
	public static function refreshCacheRoleMenu()
	{
			//刷新所有权限
			Cache::tags('roleMenu')->flush();
			return ;
	}

	/**
	* 获取所有按钮并缓存
	*/

	public static function getAllMenu()
	{
		$list = Cache::rememberForever('menulist', function () {
	   		$pid = [];
	   		$id = [];
	   		$children = [];
	   		$k = [];
	   		$list = static::orderBy('sort','asc')->orderBy('id','asc')->get();
	   		foreach($list as $key=>$value){
	   			if($value->pid === 0){
	   				$pid[$value->id] = $value;	
	   			}
	   			if($value->pid !== 0){
	   				$children[$value->pid][] = $value;
	   			}
	   			$id[$value->id] = $value;
	   			$k[$value->key] = $value;
	   		}
	   		//$returnArr = array_map(['static','sortMenu'],['pid'=>$pid,'children'=>$children,'id'=>$id]);
	   		return collect(['pid'=>$pid,'children'=>$children,'id'=>$id,'k'=>$k]);
		});
		return $list;
	}

	/**
	 * 排序
	 */
	protected static function sortMenu($arr)
	{
		return collect($arr)->sortBy('sort')->values()->all();
	}
}
