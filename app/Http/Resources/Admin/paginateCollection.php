<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\ResourceCollection;

class paginateCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return ['data'=>$this->collection->transform(function($page){
                return [
                    'id' => $page->id,
                    'title' => $page->name,
                    'slug' => $page->guard_name
                ];
            })
        ,'self'=>'111'];
    }
}
