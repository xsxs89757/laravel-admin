	#	教程
	#	使用教程
	1.复制.evn.example 改成.evn 并修改内容
	2.composer install 安装插件
	3.运行数据库迁移 
		php artisan migrate:refresh --seed 刷新数据库结构并执行数据填充
		php artisan migrate:refresh --step=1 回滚迁移
		php artisan migrate:fresh --seed 删除所有表并重新创建 
		php artisan make:migration create_users_table 
	4.nginx 配置好域名信息  分为后台api域名 前台api域名 文件域名  
	5.多语言插件 caouecs 中复制到 resources 下的lang文件夹下面
	6.文件系统  php artisan storage:link 创建符号链接
	

