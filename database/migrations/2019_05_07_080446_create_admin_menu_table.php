<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        Schema::create('admin_menu', function (Blueprint $table) use($tableNames) {
            $table->increments('id')->comment('菜单id');
            $table->string('key')->unique()->comment('菜单索引');
            $table->string('name')->comment('菜单');
            $table->string('introduction',150)->nullable()->comment('简介');
            $table->string('redirect')->comment('路由重定向 noredirect该值标示为不在面包屑中添加链接');
            $table->tinyInteger('hidden')->default(0)->comment('是否边栏隐藏,默认为0 不隐藏  1为隐藏(true)');
            $table->tinyInteger('always_show')->default(0)->comment('是否一直显示根路由 默认为0 1为显示(true)');
            $table->tinyInteger('no_cache')->default(0)->comment('是否缓存 默认为0 1为缓存(true)');
            $table->tinyInteger('breadcrumb')->default(0)->comment('是否在面包屑中隐藏 默认为0 1为隐藏(false)');
            $table->tinyInteger('is_external_link')->default('0')->comment('是否外联 默认为0 1为外链(该情况下不使用component)');
            $table->string('external_link')->nullable()->comment('外联地址,是外联的情况下该字段不为空');
            $table->tinyInteger('affix')->default(0)->comment('是否附加到导航 默认为0 1为附加(true)');
            $table->string('icon')->comment('图标');
            $table->unsignedInteger('pid')->default(0)->comment('上级菜单');
            $table->string('params')->default('')->comment('附加参数');
            $table->unsignedInteger('sort')->default(1)->comment('排序');
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
        Schema::dropIfExists('admin_menu');
    }
}
