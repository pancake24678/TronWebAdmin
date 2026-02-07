<?php

namespace addons\buiapi;

use app\common\library\Menu;
use think\Addons;

/**
 * 插件
 */
class Buiapi extends Addons
{

    /**
     * 插件安装方法
     * @return bool
     */
    public function install()
    {
        $menu = [[
            'name' => 'buiapi',
            'title' => 'API接口生成器',
            'icon' => 'fa fa-ravelry',
            'sublist' => [
                ["name" => "buiapi/index", "title" => "数据库列表"],
                ["name" => "buiapi/add", "title" => "同步数据库"],
                ["name" => "buiapi/del", "title" => "删除数据库"],
                ["name" => "buiapi/rulelist", "title" => "规则列表"],
                ["name" => "buiapi/ruleadd", "title" => "字段添加规则"],
                ["name" => "buiapi/rule_del", "title" => "字段规则删除"],
                ["name" => "buiapi/field_hidden", "title" => "字段隐藏"],
                ["name" => "buiapi/fieldview", "title" => "字段显示"],
                ["name" => "buiapi/buildindex", "title" => "生成模版"],
                ["name" => "buiapi/get_field_list", "title" => "字段列表"],
                ["name" => "buiapi/buildcommand", "title" => "生成命令"],
                ["name" => "buiapi/execcommand", "title" => "执行命令"],
				["name" => "buiapi/buildfunc", "title" => "生成方法"],
				["name" => "buiapi/buildfunction", "title" => "生成方法代码"]
            ]
        ]];
        Menu::create($menu);
        return true;
    }

    /**
     * 插件卸载方法
     * @return bool
     */
    public function uninstall()
    {
        $info = get_addon_info('buiapi');
        Menu::delete(isset($info['first_menu']) ? $info['first_menu'] : 'buiapi');
        return true;
    }

    /**
     * 插件启用方法
     * @return bool
     */
    public function enable()
    {
        $info = get_addon_info('buiapi');
        Menu::enable(isset($info['first_menu']) ? $info['first_menu'] : 'buiapi');
        return true;
    }

    /**
     * 插件禁用方法
     * @return bool
     */
    public function disable()
    {
        $info = get_addon_info('buiapi');
        Menu::disable(isset($info['first_menu']) ? $info['first_menu'] : 'buiapi');
        return true;
    }

}
