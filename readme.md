## 一 git
	1.git init 
	2.git add -A 
	3.git commit -m "first commit"
	4.git remote add origin git@github.com:xsxs89757/laravel-admin.git
	5.git clone git://github.com/schacon/grit.git mygrit //克隆仓库

## 二 .evn
	APP_URL_ADMIN	admin域名
	APP_URL_API		api域名
	修改数据库文件

## 三 JWT && laravel-cors 安装 
	composer require tymon/jwt-auth 1.*@rc
	composer require barryvdh/laravel-cors

## 四 session 认证
	php artisan make:auth

## 五  数据

### 1.数据迁移

### 2.数据填充
	composer dump-autoload 自动加载器 运行以后才能执行自动填充
	php artisan make:seeder AdminUsersTableSeeder  创建填充数据文件
	php artisan db:seed 执行所有填充
	php artisan db:seed --class=AdminUsersTableSeeder 执行单文件填充
	php artisan migrate:refresh --seed  //执行数据迁移并进行数据填充
### 3. 资源定义 API
	php artisan make:resource User 单独响应类
	php artisan make:resource CommentsCollection 定义公共响应类
### 4. 中间件创建
	php artisan make:middleware Cros