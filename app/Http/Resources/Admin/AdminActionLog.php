<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;

use App\Models\AdminMenu;

class AdminActionLog extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = $this->admin_users;
        if(empty($user)){
            $users = unserialize($this->action_user); //被删除的用户记录
        }
        $allMenu = AdminMenu::getAllMenu();
        return [
            'input' => unserialize($this->input),
            'status' => $this->status === 1?true:false,
            'method' => $this->method,
            'action_name'=> isset($allMenu['k'][$this->path_name])?$allMenu['k'][$this->path_name]['introduction']:$this->path_name,
            'action_uid' => $this->action_uid,
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
            'action_username'=>$users['username']
        ];
    }
}
