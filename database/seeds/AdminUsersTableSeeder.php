<?php

use Illuminate\Database\Seeder;

class AdminUsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admin_users')->insert([
            'username' => 'admin',
            'password' => bcrypt('123456'),
            //'phone'=>'13793303009',
            'create_time'=>time(),
            'last_login_time'=>time(),
            'last_login_ip'=>'127.0.0.1',
            'introduction'=>'', //预留
            'facephoto'=>'facephoto/default.gif',
            'nickname'=>'超级管理员'
        ]);
        DB::table('admin_users')->insert([
            'username' => 'wanglei',
            'password' => bcrypt('123456'),
            //'phone'=>'13793303008',
            'create_time'=>time(),
            'last_login_time'=>time(),
            'last_login_ip'=>'127.0.0.1',
            'introduction'=>'',//预留
            'facephoto'=>'facephoto/default.gif',
            'nickname'=>'测试1'
        ]);
    }
}
