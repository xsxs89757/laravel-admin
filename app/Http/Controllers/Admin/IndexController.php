<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
//use Illuminate\Routing\Route;

class IndexController extends Controller
{
    //
    public function show(Request $request)
    {
    	dd(Permission::all()->where('guard_name','admin'));
    	//dd(Role::all());
    	$current = \Route::currentRouteName();
    	dd($current);
    	die();
        return $current;
    }
}
