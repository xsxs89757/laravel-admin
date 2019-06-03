<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\AdminUsers;
use App\Models\AdminMenu;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        AdminMenu::refreshCachePermissionRoleMenu();
       //重置
       //app()['cache']->forget(config('permission.cache.key'));

       //初始化权限
       Permission::create(['name' => 'system']);
       Permission::create(['name' => 'system.config']);
       Permission::create(['name' => 'system.list']);
       Permission::create(['name' => 'menu']);
       Permission::create(['name' => 'menu.addMenu']);
       Permission::create(['name' => 'menu.editMenu']);
       Permission::create(['name' => 'menu.deleteMenu']);
       Permission::create(['name' => 'adminUsers']);
       Permission::create(['name' => 'adminUsers.role']);
       Permission::create(['name' => 'adminUsers.role.addRole']);
       Permission::create(['name' => 'adminUsers.role.editRole']);
       Permission::create(['name' => 'adminUsers.role.deleteRole']);
       Permission::create(['name' => 'adminUsers.list']);
       Permission::create(['name' => 'adminUsers.list.addAdminUser']);
       Permission::create(['name' => 'adminUsers.list.editAdminUser']);
       Permission::create(['name' => 'adminUsers.list.deleteAdminUser']);
       Permission::create(['name' => 'adminControllerLogs']);
       Permission::create(['name' => 'adminControllerLogs.clearAdminLogs']);
       Permission::create(['name' => 'baidu']);
       // 创建角色并分配创建的权限

       $role = Role::create(['name' => 'super-admin','create_uid' => 1]);
       $role->givePermissionTo(Permission::all()->where('guard_name','admin'));

       $role = Role::create(['name' => 'moderator','create_uid' => 1]);
       $role->givePermissionTo(['system', 'system.config','system.list']);

       $role = Role::create(['name' => 'writer','create_uid' => 1]);
       $role->givePermissionTo(['system','system.list']);

       

       

       AdminUsers::find(1)->assignRole('super-admin');  //给admin状态授权
    }
}
