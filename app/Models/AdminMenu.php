<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class AdminMenu extends BaseModel
{
   protected $guarded = [];
   static private $filterMenu = [4,5,6,7,8,9,10,11,12,13];
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
	public static function getRoleMenu($setting = false)
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
		$roleMenu = static::getRoleMenuAndHaddle($menu,$setting);
		
		return $roleMenu;
	}
	/**
	 * 获取当前可选择的上级
	 */

	public static function getChannel()
	{
		static::refreshCachePermissionRoleMenu();
		$menuPid = static::getAllMenu()->get('pid');
		$menuChildren = static::getAllMenu()->get('children');
		$menu = [];
		foreach($menuPid as $key=>$value){
			if($value->original['hidden'] === 1 || $value->original['is_external_link'] === 1){
				continue;
			}
			$pid = [
				'value'=>$value->key,
				'label'=>$value->introduction
			];
			if(array_key_exists($value->id,$menuChildren)){
				$pid['children'] = static::getChannelHaddle($value->id);
				if(empty($pid['children'])){
					unset($pid['children']);
				}
			}
			$menu[] = $pid;
		}
		return $menu;
	}

	protected static function getChannelHaddle($id){
		$menuPid = static::getAllMenu()->get('pid');
		$menuChildren = static::getAllMenu()->get('children');
		$thisMenu = $menuChildren[$id];
		$menu = [];
		foreach($thisMenu as $key=>$value){
			if($value->original['hidden'] === 1){
				continue;
			}
			$pid = [
				'value'=>$value->key,
				'label'=>$value->introduction
			];
			if(array_key_exists($value->id,$menuChildren)){
				$pid['children'] = static::getChannelHaddle($value->id);
				if(empty($pid['children'])){
					unset($pid['children']);
				}
			}
			$menu[] = $pid;
	
		}
		return $menu;
	}

	/**
	* 获取当前权限并缓存并处理
	* @protected
	*/
	protected static function getRoleMenuAndHaddle($menu,$setting = false)
	{
		$newMenu = [];
		foreach($menu as $key=>$value){
			if($setting && in_array($value->id,self::$filterMenu)){
				continue;
			}
			if($value->pid === 0){
				$value->children = static::getChildRoleMenuHaddle($menu,$value->id,$setting);
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

	protected static function getChildRoleMenuHaddle($menu,$pid,$setting)
	{
		$newMenu = [];
		foreach($menu as $key=>$value){
			if($setting && in_array($value->id,self::$filterMenu)){
				continue;
			}
			if($value->pid === $pid){
				$value->children = static::getChildRoleMenuHaddle($menu,$value->id,$setting);
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


	/**
	 * 保存
	 * @Author   lei.wang
	 * @DateTime 2019-06-04T15:43:35+0800
	 * @param    array                    $attributes [description]
	 * @return   [type]                               [description]
	 */
	public static function saveById(array $attributes = [])
    {
        $static = new static();	
        $menu = DB::transaction(function () use($static,$attributes) {
            $id = $attributes['id'];
            unset($attributes['id']);
            $oldKey = $attributes['oldKey'];
            unset($attributes['oldKey']);
            //检测同级内不允许使用相同的名称
            $menuCollect = $static::where('key',$attributes['key'])->where('id','!=',$id)->first();
            if(!$menuCollect){ 
                $menu = $static::where('id',$id)->update($attributes);
                if($oldKey !== $attributes['key']){
                	Permission::where('name',$oldKey)->update(['name'=>$attributes['key']]);
                }
                AdminMenu::refreshCacheRoleMenu(); //刷新权限缓存
                return $menu;
            }else{
                throw new \App\Exceptions\Admin\CustomException(21008);
                
            }
        });
        
        return $menu;
    }

    /**
     * 创建菜单并添加相应的权限
     * @Author   lei.wang
     * @DateTime 2019-06-04T19:00:21+0800
     * @param    array                    $attributes [description]
     * @return   [type]                               [description]
     */
    public static function createAndPermission(array $attributes=[])
    {
    	$static = new static();
    	//给该账户授权
    	$menu = DB::transaction(function () use($static,$attributes) {
    		//检测同级内不允许使用相同的名称
    		$menuCollect = $static::where('key',$attributes['key'])->first();
    		if(!$menuCollect){
                $menu = $static::query()->create($attributes);
                Permission::create(['name' => $attributes['key']]);
                $static::refreshCachePermissionRoleMenu(); //刷新权限缓存
                return $menu;
            }else{
                throw new \App\Exceptions\Admin\CustomException(21008);
                
            }
		});
		return $menu;
    }

    public static function deleteAndPermission($id)
    {
    	$deleteRows = static::find($id);
    	$children = array_keys(static::getChildrenChain($id));
    	array_unshift($children,$deleteRows->key);
		if(Permission::whereIn('name',$children)->delete()){ //删除权限
			static::whereIn('key',$children)->delete(); //删除菜单
			static::refreshCachePermissionRoleMenu(); //刷新权限缓存
		}
		return ;
    }

    /**
     * 根据父级id获取所有底层链条
     * @Author   lei.wang
     * @DateTime 2019-06-05T11:16:26+0800
     * @param    Int                      $id [description]
     * @return   Array
     */
    public static function getChildrenChain(Int $id)
    {
    	$tmp = [];
    	$children = static::getAllMenu()->get('children');
    	if(array_key_exists($id,$children)){
    		foreach($children[$id] as $key=>$value){
    			$tmp[$value->key] = $value->introduction;
    			if(array_key_exists($value->id,$children)){
    				$tmps = static::getChildrenChain($value->id);
    				$tmp = array_merge($tmp,$tmps);
    			}
    		}
    	}
    	return $tmp;

    }

    /**
     * 根据底层path  获取链条
     * @Author   lei.wang
     * @DateTime 2019-06-05T11:09:15+0800
     * @param    String                   $path [description]
     * @return   Array
     */
    public static function getParentChain(String $path)
    {
    	$tmp = [];
		$kMenu = static::getAllMenu()->get('k');
		if(array_key_exists($path,$kMenu)){
			$tmp[$kMenu[$path]->key] = $kMenu[$path]->introduction;
			if($kMenu[$path]->pid !== 0){
				$tmps = static::haddlePath($kMenu[$path]->pid);
				$tmp = array_merge($tmp,$tmps);
			}
		}
		return $tmp;

    }

    private static function haddlePath($id){
    	$tmp = [];
    	$idMenu = static::getAllMenu()->get('id');
    	if(array_key_exists($id,$idMenu)){
    		$tmp[$idMenu[$id]->key] = $idMenu[$id]->introduction;
			if($idMenu[$id]->pid !== 0){
				$tmps = static::haddlePath($idMenu[$id]->pid);
				$tmp = array_merge($tmp,$tmps);
			}
    	}
    	return $tmp;
    }

}
