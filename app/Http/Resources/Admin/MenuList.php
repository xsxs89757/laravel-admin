<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;

class MenuList extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $children = $this->children;
        return [
            'id'=>$this->id,
            'key'=>$this->key,
            'name'=>$this->name,
            'introduction'=>$this->introduction,
            'redirect'=>$this->redirect,
            'hidden'=>$this->hidden === 1?true:false,
            'always_show'=>$this->always_show === 1?true:false,
            'no_cache'=>$this->no_cache === 1?true:false,
            'is_external_link'=>$this->is_external_link === 1?true:false,
            'external_link'=>$this->external_link,
            'affix'=>$this->affix === 1?true:false,
            'icon'=>$this->icon,
            'breadcrumb'=>$this->breadcrumb === 1?true:false,
            'params'=>$this->params,
            'sort'=>$this->sort,
            'pid'=>$this->pid,
            'oldKey'=>$this->key,
            'parentKey'=>$this->when($this->pid===0,'',str_before($this->key,'.'.$this->name)),
            'children'=>$this->when(count($children) !== 0,static::collection(collect($children)))

        ];
    }
}
