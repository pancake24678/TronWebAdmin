<?php
/*
 * @Description: file content
 * @Author: xiaoyu5062
 * @QQ: 170515071
 * @E-mail: xiaoyu5062@qq.com
 * @Date: 2020-07-25 10:01:48
 */

namespace app\admin\controller\apilog;

use app\common\controller\Backend;
use addons\apilog\model\Apilog;

class Data extends Backend
{

    //基础数据
    public function index()
    {
        if (IS_AJAX) {
            $start = intval(input('start', strtotime(date("Y-m-d", time()))));
            $end = intval(input('end', $start + 86400));
            $baseinfo = Apilog::getBaseInfo($start, $end);
            $code = Apilog::getHttpCodePie($start, $end);
            $time = Apilog::getResponseTimePie($start, $end);
            $requesttop = Apilog::getMaxRequestTop($start, $end);
            $errortop = Apilog::getMaxErrorTop($start, $end);
            $fasttop = Apilog::getDoFastTop($start, $end);
            $slowtop = Apilog::getDoSlowTop($start, $end);
            $data['base'] = $baseinfo;
            $data['code'] = $code;
            $data['requesttop'] = $requesttop;
            $data['time'] = $time;
            $data['errortop'] = $errortop;
            $data['fasttop'] = $fasttop;
            $data['slowtop'] = $slowtop;
            return json($data);
        }
        return $this->view->fetch();
    }
}
