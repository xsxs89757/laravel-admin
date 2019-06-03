<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;

class Menu extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        if(isset($this['onlyOneMenu'])){
            return $this->resource;
        }
        $children = $this->children;
        if($this->pid === 0){
            $ischild = 0;
            foreach($children as $child){
                if($child['hidden'] === 1){
                    continue;
                }else{
                    $ischild++;
                }
            }
            if($ischild === 0){
                array_unshift($children,[
                    'path'=>$this->is_external_link === 1?$this->external_link:'index',
                    'name'=>$this->key.'_index',
                    'introduction'=>$this->introduction,
                    'view_template'=>str_replace('.','/',$this->key.'.index'),
                    'redirect'=>'',
                    'hidden'=>$this->hidden,
                    'always_show'=>$this->always_show,
                    'no_cache'=>$this->no_cache,
                    'is_external_link'=>$this->is_external_link,
                    'affix'=>$this->affix,
                    'icon'=>$this->icon,
                    'breadcrumb'=>$this->breadcrumb,
                    'params'=>$this->params,
                    'onlyOneMenu'=>1
                ]);
            }

        }
        return [
            'path'=>$this->name,
            'name'=>str_replace('.','_',$this->key),
            'introduction'=>$this->introduction,
            'view_template'=>str_replace('.','/',$this->key),
            'redirect'=>$this->redirect,
            'hidden'=>$this->hidden,
            'always_show'=>$this->always_show,
            'no_cache'=>$this->no_cache,
            'is_external_link'=>$this->is_external_link,
            'external_link'=>$this->when(!empty($this->external_link),$this->external_link),
            'affix'=>$this->affix,
            'icon'=>$this->icon,
            'breadcrumb'=>$this->breadcrumb,
            'params'=>$this->params,
            'children'=>$this->when(count($children) !== 0,static::collection(collect($children)))

        ];
    }
}
