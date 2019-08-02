<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;

use Illuminate\Support\Facades\Storage;

class Config extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $config_type = config('my.admin_type');
        $config_group = config('my.admin_group');
        return [
            'id'=>$this->id,
            'name'=>$this->name,
            'title'=>$this->title,
            'type'=>$this->type,
            'type_name'=>$config_type[$this->type],
            'group'=>$this->group,
            'group_name'=>$config_group[$this->group],
            'extra'=>$this->transformItem($request,parse_config_attr($this->extra),$this->extra),
            'status'=>$this->status=== 1?true:false,
            'value'=>static::checkValue($this->type,$this->value),
            'store'=>$this->store=== 1?true:false,
            'sort'=>$this->sort
        ];
    }

    /**
     * 根据规则返回数组类型存储项
     * @Author   lei.wang
     * @DateTime 2019-07-29T16:52:21+0800
     * @param    String                   $type [数据类型]
     * @param    String                   $value [原来的数据]
     * @return   String                   处理完成后的结果
     */
    static private function checkValue($type,$value){
        if(in_array($type,['checkbox','oneimage','onefile','multipleimage','multiplefile'])){
            if(in_array($type,['oneimage','onefile','multipleimage','multiplefile'])){
                $tmp = [];
                if($value){
                    $value = unserialize($value);
                    foreach($value as $key=>$value){
                        $tmp[$key] = [
                            'name'=>$value['name'],
                            'path'=>$value['path'],
                            'url'=>Storage::url($value['path'])
                        ];
                    }
                    
                }
                return $tmp;
            }else{
                return $value?unserialize($value):[]; //反序列化
            }
        }
        return $value;
    }

    /**
     * 根据传参规则决定是否转义
     * @Author   lei.wang
     * @DateTime 2019-07-29T16:54:00+0800
     * @param    Object                   $request   [获取是否返回处理还是未处理的数据]
     * @param    String                   $transform [处理完成后的数据]
     * @param    String                   $value     [需要处理的原始数据]
     * @return   String                   返回处理后的结果
     */
    private function transformItem($request,$transform,$value){
        $transformItem = $request->input('transform');
        if($transformItem == 'yes'){
            return $transform;
        }else{
            return $value;
        }
    }
}
