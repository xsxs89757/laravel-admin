<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\AdminMenu;
use App\Http\Resources\Admin\MenuList as MenuListResources;

class MenuController extends Controller
{
    /**
     * 获取按钮树形列表
     */
    protected function list()
    {
    	$menu = AdminMenu::getRoleMenu();
    	$menu = MenuListResources::collection(collect($menu));
    	$menu = $menu->resource->toArray();
    	return responseJson($menu);
    }
}
