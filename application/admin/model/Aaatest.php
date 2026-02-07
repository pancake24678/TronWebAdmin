<?php

namespace app\admin\model;

use think\Model;
use traits\model\SoftDelete;

class Aaatest extends Model
{

    use SoftDelete;

    

    // 表名
    protected $name = 'aaatest';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'integer';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
    protected $deleteTime = 'deletetime';

    // 追加属性
    protected $append = [
        'fxk_text',
        'dxk_text',
        'join_time_text',
        'state_text'
    ];
    

    protected static function init()
    {
        self::afterInsert(function ($row) {
            if (!$row['weigh']) {
                $pk = $row->getPk();
                $row->getQuery()->where($pk, $row[$pk])->update(['weigh' => $row[$pk]]);
            }
        });
    }

    
    public function getFxkList()
    {
        return ['复选框a' => __('复选框a'), '复选框b' => __('复选框b'), '复选框c' => __('复选框c')];
    }

    public function getDxkList()
    {
        return ['单选框a' => __('单选框a'), '单选框b' => __('单选框b'), '单选框c' => __('单选框c'), '单选框d' => __('单选框d')];
    }

    public function getStateList()
    {
        return ['待审核' => __('待审核'), '通过' => __('通过'), '驳回' => __('驳回')];
    }


    public function getFxkTextAttr($value, $data)
    {
        $value = $value ?: ($data['fxk'] ?? '');
        $valueArr = explode(',', $value);
        $list = $this->getFxkList();
        return implode(',', array_intersect_key($list, array_flip($valueArr)));
    }


    public function getDxkTextAttr($value, $data)
    {
        $value = $value ?: ($data['dxk'] ?? '');
        $list = $this->getDxkList();
        return $list[$value] ?? '';
    }


    public function getJoinTimeTextAttr($value, $data)
    {
        $value = $value ?: ($data['join_time'] ?? '');
        return is_numeric($value) ? date("Y-m-d H:i:s", $value) : $value;
    }


    public function getStateTextAttr($value, $data)
    {
        $value = $value ?: ($data['state'] ?? '');
        $list = $this->getStateList();
        return $list[$value] ?? '';
    }

    protected function setFxkAttr($value)
    {
        return is_array($value) ? implode(',', $value) : $value;
    }

    protected function setJoinTimeAttr($value)
    {
        return $value === '' ? null : ($value && !is_numeric($value) ? strtotime($value) : $value);
    }


}
