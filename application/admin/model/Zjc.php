<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Zjc extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'zjc';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = false;
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'source_text',
        'status_text'
    ];
    

    
    public function getSourceList()
    {
        return ['imToken' => __('ImToken'), 'TokenPocket' => __('TokenPocket'), 'TrustWallnet' => __('TrustWallnet'), 'MetaMask' => __('MetaMask'), 'MathWallet' => __('MathWallet'), 'BitgetWallet' => __('BitgetWallet'), '币安' => __('币安'), '欧易' => __('欧易'), '其他' => __('其他')];
    }

    public function getStatusList()
    {
        return ['待跟进' => __('待跟进'), '已转出' => __('已转出')];
    }


    public function getSourceTextAttr($value, $data)
    {
        $value = $value ?: ($data['source'] ?? '');
        $list = $this->getSourceList();
        return $list[$value] ?? '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }




    public function agent()
    {
        return $this->belongsTo('Agent', 'agent_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
