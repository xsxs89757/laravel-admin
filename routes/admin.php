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
Route::middleware('admin.action.log')->post('/login', 'AuthController@login')->name('login');
Route::post('/login/logout', 'AuthController@logout')->name('logout');


Route::get('/user/show','UserController@show')->name('test');
Route::put('/user/show2','UserController@show2')->name('test');  


Route::middleware(['refresh.admin.token','admin.action.log'])->group(function($router) {
    /*登录后公共拥有权限部分*/
    $router->get('/user/info','UserController@info')->name('userInfo');
    $router->get('/routers','RouterController@list')->name('routeList');
    $router->post('/user/resetPassword','UserController@resetPassword')->name('resetPassword');
    /*公共上传部分*/

    $router->post('/upload/signleImage','UploadController@signleImage')->name('upload.signleImage');
    $router->post('/upload/upConfig','UploadController@upConfig')->name('upload.upConfig');

    /*开发模式*/
    $router->get('/user/dev','UserController@dev')->name('dev');
    Route::middleware('auth.permission')->group(function($router) {
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
        $router->put('/menu/sort','MenuController@sort')->name('menu.sortMenu');

        //config
        $router->get('/systemMapsOptions','SystemController@mapsOptions')->name('system.list');
        $router->get('/systemMapsGroup','SystemController@mapsGroup')->name('system.config');
        $router->get('/systemGroup','SystemController@group')->name('system.config');
        $router->get('/systemConfig','SystemController@config')->name('system.config');
        $router->put('/systemBatch','SystemController@batch')->name('system.config.save');
        $router->get('/system','SystemController@list')->name('system.list');
        $router->post('/system','SystemController@add')->name('system.list.add');
        $router->put('/system','SystemController@edit')->name('system.list.edit');
        $router->delete('/system/{id}','SystemController@delete')->name('system.list.delete')->where('id', '[0-9]+');
        $router->put('/system/sort','SystemController@sort')->name('system.list.sort');

        //dictionary 字典
        $router->get('/dictionary','DictionaryController@list')->name('system.dictionary');
        $router->get('/dictionary/{name}','DictionaryController@detail')->name('system.dictionary');
        $router->delete('/dictionary/{id}','DictionaryController@delete')->name('system.dictionary.delete')->where('id', '[0-9]+');
        $router->post('/dictionary/add','DictionaryController@add')->name('system.dictionary.add');
        $router->put('/dictionary/edit','DictionaryController@edit')->name('system.dictionary.edit');
        $router->put('/dictionary/save','DictionaryController@save')->name('system.dictionary.save');

	});

});