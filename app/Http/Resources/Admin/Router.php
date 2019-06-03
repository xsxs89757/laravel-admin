<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class Router extends Resource
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
            'path' => $this->getRoleNames(),
            'component' => $this->introduction,
            'avatar' => $this->facephoto,
            'name' => $this->nickname,
        ];
    }
}
