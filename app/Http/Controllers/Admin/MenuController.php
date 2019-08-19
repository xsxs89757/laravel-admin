<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\AdminMenu;
use App\Http\Resources\Admin\MenuList as MenuListResources;

class MenuController extends AdminApiController
{
    /**
     * 获取按钮树形列表
     */
    protected function list()
    {
    	$menu = AdminMenu::getRoleMenu();
    	$menu = MenuListResources::collection(collect($menu));
    	$menu = $menu->resource->toArray();
        $channel = AdminMenu::getChannel();
        $return = [
            'menu'=>$menu,
            'channel'=>$channel
        ];
    	return $this->success($return);
    }

    /**
     * 添加菜单
     */
    
    protected function add(Request $request)
    {
        $rules = [
            'name'   => 'required|alpha',
            'introduction' => 'required'
        ];
        $this->validate($request, $rules);
        $key = $request->input('parentKey');
        $keyMenuAll = AdminMenu::getAllMenu()->get('k');
        $pid = 0;
        if(array_key_exists($key,$keyMenuAll)){
            $pid = $keyMenuAll[$key]->id;
        }
        $attributes = [
            'key'=>empty($key)?$request->input('name'):$key.'.'.$request->input('name'),
            'name'=>$request->input('name'),
            'introduction'=>$request->input('introduction'),
            'redirect'=>$request->input('redirect'),
            'hidden'=>$request->input('hidden')?1:0,
            'always_show'=>$request->input('always_show')?1:0,
            'no_cache'=>$request->input('no_cache')?1:0,
            'breadcrumb'=>$request->input('breadcrumb')?1:0,
            'is_external_link'=>$request->input('is_external_link')?1:0,
            'external_link'=>$request->input('external_link'),
            'affix'=>$request->input('affix')?1:0,
            'icon'=>$request->input('icon'),
            'pid'=>$pid,
            'params'=>$request->input('params'),
            'sort'=>$request->input('sort')<0?1:$request->input('sort')
        ];
        $menu = AdminMenu::createAndPermission($attributes);
        if($menu){
            return $this->message(trans('form.addSuccess'));
        }else{
            throw new \App\Exceptions\Admin\CustomException(21002);
        }
        
    }

    /**
     * 编辑菜单
     */
    protected function edit(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'name' => 'required|alpha',
            'introduction' => 'required',

        ];
        $this->validate($request, $rules);
        $key = $request->input('parentKey');
        $keyMenuAll = AdminMenu::getAllMenu()->get('k');
        $pid = 0;
        if(array_key_exists($key,$keyMenuAll)){
            $pid = $keyMenuAll[$key]->id;
        }
        $attributes = [
            'id'=>$request->input('id'),
            'key'=>empty($key)?$request->input('name'):$key.'.'.$request->input('name'),
            'name'=>$request->input('name'),
            'introduction'=>$request->input('introduction'),
            'redirect'=>$request->input('redirect'),
            'hidden'=>$request->input('hidden')?1:0,
            'always_show'=>$request->input('always_show')?1:0,
            'no_cache'=>$request->input('no_cache')?1:0,
            'breadcrumb'=>$request->input('breadcrumb')?1:0,
            'is_external_link'=>$request->input('is_external_link')?1:0,
            'external_link'=>$request->input('external_link'),
            'affix'=>$request->input('affix')?1:0,
            'icon'=>$request->input('icon'),
            'pid'=>$pid,
            'oldKey'=>$request->input('oldKey'),
            'params'=>$request->input('params'),
            'sort'=>$request->input('sort')<0?1:$request->input('sort')
        ];
        $menu = AdminMenu::saveById($attributes);
        if($menu){
            return $this->message(trans('form.editSuccess'));
        }else{
            throw new \App\Exceptions\Admin\CustomException(21003);
        }
    }

    /**
     * 删除菜单
     */
    protected function delete($id)
    {
        AdminMenu::deleteAndPermission($id);
        return $this->message('');
    }

    protected function sort(Request $request)
    {
        $input = $request->input();
        if($input){
            $tmp = [];
            foreach ($input as $key => $value) {
                $tmp[] =  collect($value)->toArray();
            }
            $updateCount = AdminMenu::updateBatch('admin_menu',$tmp);
            AdminMenu::refreshCachePermissionRoleMenu();
            return $this->success(['update_count'=>$updateCount]);
        }
        
    }
}
