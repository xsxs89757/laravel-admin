<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;

use App\Models\Config;
use App\Models\Dictionary;

class AdminUser extends Resource
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
            'roles' => $this->getRoleNames(),
            'introduction' => $this->introduction,
            'avatar' => Storage::url($this->facephoto),
            'name' => $this->nickname,
            'is_admin' => $this->when($this->id === 1,1),
            'config' => Config::getStoreConfig(),
            'dictionary' =>Dictionary::getDictionary()
        ];
    }
}
