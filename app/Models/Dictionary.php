<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dictionary extends BaseModel
{
    protected $guarded = [];

    /**
     * 添加字典
     * @Author   lei.wang
     * @DateTime 2019-08-01T18:30:00+0800
     * @param    array                    $attributes
     * @return   array
     */
    public static function createById(array $attributes=[])
    {
    	$dictionary = static::where('name',$attributes['name'])->first();
    	if(!$dictionary){
 			$dictionary = static::query()->create($attributes);
 			//更新通用配置缓存
 			static::refreshCache();
    	}else{
    		throw new \App\Exceptions\Admin\CustomException(21011);
    	}
    	return $dictionary;
    }

    /**
     * 编辑字典
     * @Author   lei.wang
     * @DateTime 2019-08-02T10:00:17+0800
     * @param    array                    $attributes
     * @return   array
     */
    public static function saveById($attributes)
    {
    	$id = $attributes['id'];
        unset($attributes['id']);
    	$dictionary = static::where('name',$attributes['name'])->where('id','!=',$id)->first();
    	if(!$dictionary){
    		$dictionary = static::where('id',$id)->update($attributes);
 			//更新通用配置缓存
 			static::refreshCache();
    	}else{
    		throw new \App\Exceptions\Admin\CustomException(21011);
    	}
    	return $dictionary;
    }

    /**
     * 更新字典缓存
     * @Author   lei.wang
     * @DateTime 2019-08-01T18:30:54+0800
     * @return   
     */
    public static function refreshCache()
    {

    }
}
