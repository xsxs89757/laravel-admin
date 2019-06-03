<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
	//重置表名被自动添加复数S的方法
    public function getTable(){
        return $this->table?$this->table:strtolower(snake_case(class_basename($this)));
    }
}
