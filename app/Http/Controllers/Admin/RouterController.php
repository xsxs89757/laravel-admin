<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

//model
use App\Models\AdminMenu;
use App\Models\Roles;

//Resources
use App\Http\Resources\Admin\Permission  as PermissionResources;
use App\Http\Resources\Admin\Menu as MenuResources;

class RouterController extends AdminApiController
{
    
    /** 
     * 获取登录用户router列表
     */
    
    protected function list(Request $request)
    {
        $setting = $request->input('setting');
    	$menu = AdminMenu::getRoleMenu($setting==="1"?true:false);
    	$menu = MenuResources::collection(collect($menu));
    	$menu = $menu->resource->toArray();
    	$menuAll = AdminMenu::getAllMenu();
    	$return = [
    		'menu'=>$menu,
    		'permission'=>Roles::getUsersPermissions()
    	];
    	return $this->success($return);
    }
}
