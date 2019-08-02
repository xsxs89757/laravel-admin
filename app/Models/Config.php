<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

class Config extends BaseModel
{
	protected $guarded = [];
	/**
	 * 添加配置
	 * @Author   lei.wang
	 * @DateTime 2019-06-18T16:39:54+0800
	 * @param    array                    $attributes
	 * @return   Object
	 */
    public static function createById(array $attributes=[])
    {
    	$config = static::where('name',$attributes['name'])->first();
    	if(!$config){
 			$config = static::query()->create($attributes);
 			//更新通用配置缓存
 			static::refreshCache();
    	}else{
    		throw new \App\Exceptions\Admin\CustomException(21010);
    	}
    	return $config;
    }

    /**
	 * 保存
	 * @Author   lei.wang
	 * @DateTime 2019-06-04T15:43:35+0800
	 * @param    array                    $attributes [description]
	 * @return   [type]                               [description]
	 */
	public static function saveById(array $attributes = [])
    {
    	$id = $attributes['id'];
    	unset($attributes['id']);
    	$config = static::where('name',$attributes['name'])->where('id','!=',$id)->first();
    	if(!$config){
 			$config = static::where('id',$id)->update($attributes);
 			//更新通用配置缓存
 			static::refreshCache();
    	}else{
    		throw new \App\Exceptions\Admin\CustomException(21010);
    	}
    	return $config;
    }
    /**
     * 刷新配置缓存
     * @Author   lei.wang
     * @DateTime 2019-06-18T18:13:05+0800
     */
    public static function refreshCache()
    {
        Cache::forget('systemConfig');
        Cache::forget('systemStoreConfig');
    }

    
    /**
     * 获取配置项
     * @Author   lei.wang
     * @DateTime 2019-07-31T14:42:22+0800
     * @param    String|null                   $name [配置别名]
     * @return   String|Array                  返回单项配置或整体配置数组
     */
    public static function getConfig(String $name=null)
    {
        $config = Cache::rememberForever('systemConfig', function () {
            $list = static::select('name','type','value')->get();
            $config = [];
            foreach($list as $key=>$value){
                if(in_array($value->type,['checkbox','oneimage','onefile','multipleimage','multiplefile'])){
                    $config[$value->name] = unserialize($value->value);
                }elseif(in_array($value->type,['dictionary'])){
                    $config[$value->name] = parse_config_attr($value->value);
                }else{
                    $config[$value->name] = $value->value;
                }
            }
            return $config;
        });
        return $name?$config['name']:$config;
    }

    /**
     * 获取前端配置项
     * @Author   lei.wang
     * @DateTime 2019-07-31T14:43:29+0800
     * @param    String|null                   $name [配置别名]
     * @return   String|Array                  返回单项配置或整体配置数组
     */
    public static function getStoreConfig(String $name=null)
    {
        $config = Cache::rememberForever('systemStoreConfig', function () {
            $list = static::select('name','type','value')->where('store',1)->get();
            $config = [];
            foreach($list as $key=>$value){
                if(in_array($value->type,['checkbox','oneimage','onefile','multipleimage','multiplefile'])){
                    $config[$value->name] = unserialize($value->value);
                }elseif(in_array($value->type,['dictionary'])){
                    $config[$value->name] = parse_config_attr($value->value);
                }else{
                    $config[$value->name] = $value->value;
                }
            }
            return $config;
        });
        return $name?$config['name']:$config;
    }
}
