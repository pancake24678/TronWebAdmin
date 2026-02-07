<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Messages extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'messages';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'message_type_text'
    ];
    

    
    public function getMessageTypeList()
    {
        return ['普通消息' => __('普通消息'), '系统通知' => __('系统通知'), '公告' => __('公告')];
    }


    public function getMessageTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['message_type'] ?? '');
        $list = $this->getMessageTypeList();
        return $list[$value] ?? '';
    }




    public function admin()
    {
        return $this->belongsTo('Admin', 'admin_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
