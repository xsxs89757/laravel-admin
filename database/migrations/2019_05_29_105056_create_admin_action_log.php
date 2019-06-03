<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminActionLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_action_log', function (Blueprint $table) {
            $table->increments('id')->comment('日志id');
            $table->text('input')->comment('操作内容');
            $table->tinyInteger('status')->default(1)->comment('是否成功');
            $table->string('path')->comment('操作的路由');
            $table->string('path_name')->commit('操作的路由名称');
            $table->string('ip',15)->comment('操作ip');
            $table->string('method')->commit('请求方式');
            $table->unsignedInteger('action_uid')->commit('操作人');
            $table->text('action_user')->commit('操作人详细资料,防止被删除后找不到详细资料');
            $table->timestamps();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_action_log');
    }
}
