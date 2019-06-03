<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*test*/
Route::get('/', 'IndexController@show')->name('show');


//login
Route::post('/login', 'AuthController@login')->name('login');
Route::post('/login/logout', 'AuthController@logout')->name('logout');

Route::middleware('auth.permission')->group(function($router) {
    $router->get('/user/show','UserController@show')->name('test');
    $router->get('/user/show2','UserController@show2')->name('test');  
});

Route::middleware('refresh.admin.token')->group(function($router) {
    /*登录后公共拥有权限部分*/
    $router->get('/user/info','UserController@info')->name('userInfo');
    $router->get('/routers','RouterController@list')->name('routeList');
    /*公共上传部分*/
    $router->post('/upload/signleImage','UploadController@signleImage')->name('upload.signleImage');

    Route::middleware(['auth.permission','admin.action.log'])->group(function($router) {
        /**
         * 下面的根据name路由来区分是否有权限
         */
        
    	/*role管理*/
    	$router->get('/roles','RolesController@list')->name('adminUsers.role');
    	$router->post('/roles','RolesController@add')->name('adminUsers.role.addRole');
    	$router->delete('/roles/{id}','RolesController@delete')->name('adminUsers.role.deleteRole')->where('id', '[0-9]+');
    	$router->put('/roles','RolesController@edit')->name('adminUsers.role.editRole');
    	
        /*adminUsers管理*/
        $router->get('/users','UserController@list')->name('adminUsers.list');
        $router->put('/users','UserController@edit')->name('adminUsers.list.editAdminUser');
        $router->delete('/users/{id}','UserController@delete')->name('adminUsers.list.deleteAdminUser')->where('id', '[0-9]+');
        $router->post('/users','UserController@add')->name('adminUsers.list.addAdminUser');
        //日志
        $router->get('/adminLogs','UserController@logs')->name('adminControllerLogs');
        $router->delete('/clearAdminLogs','UserController@clearLogs')->name('adminControllerLogs.clearAdminLogs');
        //menu
        $router->get('/menu','MenuController@list')->name('menu');
        $router->post('/menu','MenuController@add')->name('menu.addMenu');
        $router->put('/menu','MenuController@edit')->name('menu.editMenu');
        $router->delete('/menu/{id}','MenuController@delete')->name('menu.deleteMenu')->where('id', '[0-9]+');

	});

});