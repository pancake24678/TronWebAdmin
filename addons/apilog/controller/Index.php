<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: xiaoyu5062
 * @QQ/Email: xiaoyu5062@qq.com
 * @Date: 2020-07-25 10:01:48
 * @LastEditors: xiaoyu5062
 * @LastEditTime: 2025-02-19 16:23:40
 */

namespace addons\apilog\controller;

use addons\apilog\model\Apilog;
use think\addons\Controller;

class Index extends Controller
{

    public function index()
    {
        return $this->view->fetch();
    }
}
