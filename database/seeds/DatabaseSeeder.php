<?php

use Illuminate\Database\Seeder;

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
        $this->call(AdminMenuSeeder::class); //菜单
        $this->call(RolesAndPermissionsSeeder::class); //权限
        
    }
}
