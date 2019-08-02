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
    private $action_name = ['login'=>'登录','resetPassword'=>'修改密码'];
    public function toArray($request)
    {
        $user = $this->admin_users;
        if(empty($user)){
            $users = unserialize($this->action_user); //被删除的用户记录
        }
        $path = implode('/',array_reverse(array_values(AdminMenu::getParentChain($this->path_name))));
        if(!$path){
            if(array_key_exists($this->path_name,$this->action_name)){
                $path = $this->action_name[$this->path_name];
            }else{
                $path = '上传文件{'.$this->path_name.'}';
            }
        }
        return [
            'input' => unserialize($this->input),
            'status' => $this->status === 1?true:false,
            'method' => $this->method,
            'action_name'=> $path,
            'action_uid' => $this->action_uid,
            'created_at'=>$this->created_at->format('Y-m-d H:i:s'),
            'action_username'=>$users['username']?$users['username']:trans('auth.noLogin'),
            'ip'=>$this->ip
        ];
    }
}
