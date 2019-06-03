<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BaseModel;

class AdminMenuRole extends BaseModel
{
    
	public static function create(array $attributes = [])
    {
    	//调试
        return static::query()->create($attributes);
    }



}
