<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 助记词管理
 *
 * @icon fa fa-circle-o
 */
class Zjc extends Backend
{

    /**
     * Zjc模型对象
     * @var \app\admin\model\Zjc
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Zjc;
        $this->view->assign("sourceList", $this->model->getSourceList());
        $this->view->assign("statusList", $this->model->getStatusList());
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 查看
     */
    public function index()
    {
        // 从filter中解析status并设置到GET参数
        $filter = $this->request->get('filter');
        if ($filter) {
            $filterArr = json_decode($filter, true);
            if (isset($filterArr['status'])) {
                $this->request->get(['status' => $filterArr['status']]);
            }
        }

        // 默认筛选待跟进状态
        if (!$this->request->has('status')) {
            $this->request->get(['status' => '待跟进']);
        }

        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                    ->with(['agent'])
                    ->where($where)
                    ->order($sort, $order)
                    ->paginate($limit);

            foreach ($list as $row) {
                
                $row->getRelation('agent')->visible(['name']);
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    private function getAddressByMnemonics($mnemonics)
    {
        //TODO 通过助记词获取地址
        
        return '';
    }
}
