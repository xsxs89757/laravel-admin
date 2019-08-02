<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConfig extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->increments('id')->comment('配置id');
            $table->string('name')->unique()->comment('配置名称');
            $table->string('type')->index()->comment('配置类型');
            $table->string('title')->comment('配置说明');
            $table->string('group')->index()->comment('配置分组');
            $table->string('extra')->nullable()->comment('配置值 - 数字,字符串,密码');
            $table->string('remark')->nullable()->comment('配置说明');
            $table->tinyInteger('status')->default(1)->comment('状态');
            $table->text('value')->nullable()->comment('配置值 - 文本,枚举,编辑器');
            $table->tinyInteger('store')->defaule(0)->comment('是否发送至前端使用');
            $table->tinyInteger('sort')->defaule(1)->comment('是否发送至前端使用'); //时间倒序  序号正序
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
        Schema::dropIfExists('config');
    }
}
