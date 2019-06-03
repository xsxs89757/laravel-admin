<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;
use App\Models\AdminUsers as AdminUsersModel;
use Illuminate\Support\Facades\Storage;

class AdminUsers extends Resource
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
            'id' => $this->id,
            'username' => $this->username,
            'nickname' => $this->nickname,
            'facephoto_url' =>Storage::url($this->facephoto),
            'facephoto' => $this->facephoto,
            'roles' => AdminUsersModel::find($this->id)->getRoleNames(),
            'create_time' => date('Y-m-d H:i:s',$this->create_time),
            'last_login_time' => date('Y-m-d H:i:s',$this->last_login_time),
            'last_login_ip' => $this->last_login_ip,
            'status' => $this->status===1?true:false,
            'create_users' => $this->adminUsers
        ];
    }
}
