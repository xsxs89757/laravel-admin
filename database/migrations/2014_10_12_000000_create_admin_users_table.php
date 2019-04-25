<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_users', function (Blueprint $table) {
            $table->increments('id')->comment('后台用户id');
            $table->string('username')->comment('后台用户名');
            $table->tinyInteger('status')->default(1)->comment('状态');
            $table->string('phone')->nullable()->comment('手机号');
            $table->string('password',60)->comment('密码');
            $table->Integer('create_time')->comment('创建时间');
            $table->Integer('last_login_time')->comment('最后登录时间');
            $table->string('last_login_ip',15)->comment('最后登录ip');
            $table->string('facephoto',150)->nullable()->comment('头像');
            $table->string('nickname',50)->comment('真实名称');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_users');
    }
}
