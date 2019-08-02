<?php

use Illuminate\Database\Seeder;

use App\Models\AdminMenu; //判断是否为空  为空填充初始化数据  不然不填充

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminUsersTableSeeder::class); //会员
        $count = AdminMenu::count();
        if($count==0){
            $this->call(AdminMenuSeeder::class); //菜单
            $this->call(RolesAndPermissionsSeeder::class); //权限
        }

        
    }
}
