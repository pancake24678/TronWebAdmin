<?php

namespace app\admin\model;

use think\Model;


class MessageLog extends Model
{

    

    

    // 表名
    protected $name = 'message_log';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'read_time_text'
    ];
    

    



    public function getReadTimeTextAttr($value, $data)
    {
        $value = $value ?: ($data['read_time'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }

    protected function setReadTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


    public function messages()
    {
        return $this->belongsTo('Messages', 'message_id', 'id', [], 'LEFT')->setEagerlyType(0);
    }
}
