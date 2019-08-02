	#	教程
	#	使用教程
	1.复制.evn.example 改成.evn 并修改内容
	2.运行数据库迁移 
		php artisan migrate:refresh --seed 刷新数据库结构并执行数据填充
		php artisan migrate:refresh --step=1 //回滚迁移
		php artisan migrate:fresh --seed //删除所有表并重新创建 
		php artisan make:migration create_users_table 
	3.nginx 配置好域名信息
	4.多语言插件 caouecs 中复制到 resources 下的lang文件夹下面
	5.文件系统  php artisan storage:link 创建符号链接
	

