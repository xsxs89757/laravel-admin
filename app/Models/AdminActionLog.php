<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminActionLog extends BaseModel
{
    /**
     * 绑定后台会员表
     */

    public function adminUsers()
    {
    	return $this->belongsTo('App\Models\AdminUsers','action_uid');
    }
}
