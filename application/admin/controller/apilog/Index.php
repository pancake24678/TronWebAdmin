<?php
/*
 * @Descripttion: 
 * @version: 
 * @Author: xiaoyu5062
 * @QQ: 170515071
 * @Email: xiaoyu5062@qq.com
 * @Date: 2020-07-25 10:01:48
 * @LastEditors: xiaoyu5062
 * @LastEditTime: 2025-02-19 17:02:04
 */

namespace app\admin\controller\apilog;

use app\common\controller\Backend;
use think\Cache;
use think\Db;

class Index extends Backend
{

    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \addons\apilog\model\Apilog;
        $this->view->assign("methodList", $this->model->getMethodList());
    }

    public function index()
    {
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                ->where($where)
                ->order($sort, $order)
                ->count();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            foreach ($list as $k => $v) {
                $v['banip'] = Cache::has('banip:' . $v['ip']);
            }
            $list = collection($list)->toArray();
            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }


    public function detail($ids)
    {
        $row = $this->model->get(['id' => $ids]);
        if (!$row)
            $this->error(__('No Results were found'));
        $this->view->assign("row", $row->toArray());
        return $this->view->fetch();
    }


    public function banip($status, $ip, $time = 0)
    {
        if ($status == 0) {
            Cache::set('banip:' . $ip, 1, $time * 60);
        } else {
            Cache::rm('banip:' . $ip);
        }
        $this->success('succ', null, Cache::has('banip:' . $ip));
    }


    public function clear()
    {
        $tableName = $this->model->getTable();
        try {
            // 尝试使用 TRUNCATE 语句清空数据表
            Db::execute("TRUNCATE TABLE {$tableName}");
            $this->success('清空成功');
        } catch (\Exception $e) {
            // TRUNCATE 失败，捕获异常并尝试使用 DELETE 语句
            try {
                // 开启事务
                Db::startTrans();
                // 执行 DELETE 操作清空表数据
                Db::name($tableName)->delete(true);
                // 提交事务
                Db::commit();
                $this->success('清空成功');
            } catch (\Exception $deleteException) {
                // DELETE 也失败，回滚事务并返回错误信息
                Db::rollback();
                $this->error('清空失败:'+ $deleteException->getMessage());
            }
        }
    }
}
