<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use App\Http\Resources\Admin\CommentsCollection;

class LoginController extends Controller
{
    /**
     * 
     */
    protected function login(){
    	$data = AdminUser::all();
    	//$collect = collect(['data'=>$data->toArray(),'code'=>50008]);
    	$commentsCollection = new CommentsCollection($data);
    	//var_dump($commentsCollection);
    	//$commentsCollection->code = 50008;
    	return responseJson($commentsCollection,'');
    }
}
