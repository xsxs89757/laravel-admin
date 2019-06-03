<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;
use App\Models\AdminMenu;
use App\Http\Resources\Admin\Menu as MenuResources;

class Roles extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'guard_name'=>$this->guard_name,
            'create_username'=>$this->adminUsers->username,
            'create_nickname'=>$this->adminUsers->nickname?$this->adminUsers->nickname:$this->adminUsers->username,
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
            'updated_at'=>$this->updated_at->format('Y-m-d H:i:s'),
            'routers'=>MenuResources::collection(collect(AdminMenu::getRoleMenuParam($this->permissions)))
        ];
    }
}
