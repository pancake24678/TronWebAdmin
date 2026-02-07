<?php

namespace app\admin\controller;

use think\Controller;

/**
 * cli
 *
 * @icon fa fa-circle-o
 */
class Cli extends Controller
{
    public function test()
    {
        // 第一层：PHP缓冲
        ob_start();

        // 第二层：系统调用缓冲
        for ($i = 1; $i <= 5; $i++) {
            echo "第{$i}次输出\n";
            sleep(1);
            
            // 刷新PHP缓冲
            ob_flush();
            
            // 刷新系统缓冲
            flush();
        }

        ob_end_flush();
    }
}
