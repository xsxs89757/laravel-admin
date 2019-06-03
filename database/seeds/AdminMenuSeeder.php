<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\AdminUsers;
use App\Models\AdminMenu;

class AdminMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminMenu = AdminMenu::create(['name'=>'system','key'=>'system','introduction'=>'系统设置','redirect'=>'config','hidden'=>0,'breadcrumb'=>0,
        					'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
        					'icon'=>'setting','pid'=>0,'params'=>'','sort'=>99]);

        AdminMenu::create(['name'=>'config','key'=>'system.config','introduction'=>'参数设置','redirect'=>'','hidden'=>0,'breadcrumb'=>0,
        					'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
        					'icon'=>'','pid'=>$adminMenu->id,'params'=>'','sort'=>1]);
        AdminMenu::create(['name'=>'list','key'=>'system.list','introduction'=>'配置管理','redirect'=>'','hidden'=>0,'breadcrumb'=>0,
        					'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
        					'icon'=>'','pid'=>$adminMenu->id,'params'=>'','sort'=>2]);

        $adminMenu = AdminMenu::create(['name'=>'menu','key'=>'menu','introduction'=>'菜单管理','redirect'=>'index','hidden'=>0,'breadcrumb'=>0,
        					'always_show'=>0,'no_cache'=>1,'is_external_link'=>0,'affix'=>0,
        					'icon'=>'list','pid'=>0,'params'=>'','sort'=>99]);
        AdminMenu::create(['name'=>'addMenu','key'=>'menu.addMenu','introduction'=>'添加','redirect'=>'','hidden'=>1,'breadcrumb'=>0,
                            'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
                            'icon'=>'','pid'=>$adminMenu->id,'params'=>'','sort'=>1]);
        AdminMenu::create(['name'=>'editMenu','key'=>'menu.editMenu','introduction'=>'编辑','redirect'=>'','hidden'=>1,
                            'breadcrumb'=>0,'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
                            'icon'=>'','pid'=>$adminMenu->id,'params'=>'','sort'=>1]);
        AdminMenu::create(['name'=>'deleteMenu','key'=>'menu.deleteMenu','introduction'=>'删除','redirect'=>'','hidden'=>1,
                            'breadcrumb'=>0,'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
                            'icon'=>'','pid'=>$adminMenu->id,'params'=>'','sort'=>1]);

        $adminMenu = AdminMenu::create(['name'=>'adminUsers','key'=>'adminUsers','introduction'=>'管理员','redirect'=>'list','hidden'=>0,
        					'breadcrumb'=>0,'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
        					'icon'=>'user','pid'=>0,'params'=>'','sort'=>99]);

        $adminMenuRole = AdminMenu::create(['name'=>'role','key'=>'adminUsers.role','introduction'=>'角色管理','redirect'=>'','hidden'=>0,
                                            'breadcrumb'=>0,'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
        					               'icon'=>'','pid'=>$adminMenu->id,'params'=>'','sort'=>1]);
        AdminMenu::create(['name'=>'addRole','key'=>'adminUsers.role.addRole','introduction'=>'添加角色','redirect'=>'','hidden'=>1,'breadcrumb'=>0,
                            'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
                            'icon'=>'','pid'=>$adminMenuRole->id,'params'=>'','sort'=>1]);
        AdminMenu::create(['name'=>'editRole','key'=>'adminUsers.role.editRole','introduction'=>'编辑角色','redirect'=>'','hidden'=>1,
                            'breadcrumb'=>0,'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
                            'icon'=>'','pid'=>$adminMenuRole->id,'params'=>'','sort'=>1]);
        AdminMenu::create(['name'=>'deleteRole','key'=>'adminUsers.role.deleteRole','introduction'=>'删除角色','redirect'=>'','hidden'=>1,
                            'breadcrumb'=>0,'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
                            'icon'=>'','pid'=>$adminMenuRole->id,'params'=>'','sort'=>1]);

        $adminMenuUser = AdminMenu::create(['name'=>'list','key'=>'adminUsers.list','introduction'=>'管理员管理','redirect'=>'','hidden'=>0,
                                            'breadcrumb'=>0,'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
        					               'icon'=>'','pid'=>$adminMenu->id,'params'=>'','sort'=>1]);
        AdminMenu::create(['name'=>'addAdminUser','key'=>'adminUsers.list.addAdminUser','introduction'=>'添加','redirect'=>'','hidden'=>1,
                            'breadcrumb'=>0,'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
        					'icon'=>'','pid'=>$adminMenuUser->id,'params'=>'','sort'=>1]);
        AdminMenu::create(['name'=>'editAdminUser','key'=>'adminUsers.list.editAdminUser','introduction'=>'编辑','redirect'=>'','hidden'=>1,
                            'breadcrumb'=>0,'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
        					'icon'=>'','pid'=>$adminMenuUser->id,'params'=>'','sort'=>1]);
        AdminMenu::create(['name'=>'deleteAdminUser','key'=>'adminUsers.list.deleteAdminUser','introduction'=>'删除','redirect'=>'','hidden'=>1,
        					'breadcrumb'=>0,'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
        					'icon'=>'','pid'=>$adminMenuUser->id,'params'=>'','sort'=>1]);

        $adminUsersLogs = AdminMenu::create(['name'=>'adminControllerLogs','key'=>'adminControllerLogs','introduction'=>'操作日志','redirect'=>'index'							,'hidden'=>0,'breadcrumb'=>0,'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
        					'icon'=>'eye','pid'=>0,'params'=>'','sort'=>99]);
        AdminMenu::create(['name'=>'clearAdminLogs','key'=>'adminControllerLogs.clearAdminLogs','introduction'=>'清空日志','redirect'=>''
                            ,'hidden'=>1,'breadcrumb'=>0,'always_show'=>0,'no_cache'=>0,'is_external_link'=>0,'affix'=>0,
                            'icon'=>'','pid'=>$adminUsersLogs->id,'params'=>'','sort'=>1]);

        AdminMenu::create(['name'=>'baidu','key'=>'baidu','introduction'=>'百度','redirect'=>'index','hidden'=>0,'breadcrumb'=>0,'always_show'=>0,
                            'no_cache'=>0,'is_external_link'=>1,'external_link'=>'http://www.baidu.com','affix'=>0,
                            'icon'=>'link','pid'=>0,'params'=>'','sort'=>99]);

    }
}
