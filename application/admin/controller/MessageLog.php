<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use Exception;
use PDOException;
use think\Db;

/**
 * 消息接收管理
 *
 * @icon fa fa-circle-o
 */
class MessageLog extends Backend
{

    /**
     * MessageLog模型对象
     * @var \app\admin\model\MessageLog
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\MessageLog;
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
                ->with(['messages'])
                ->where($where)
                ->where('message_log.admin_id', $this->auth->id)
                ->order($sort, $order)
                ->order('message_log.id', 'desc')
                ->paginate($limit);

            foreach ($list as $row) {
                $row->visible(['id', 'is_read', 'read_time', 'createtime', 'updatetime']);
                $row->visible(['messages']);
                $row->getRelation('messages')->visible(['message_type', 'title', 'content', 'fj_file']);
            }

            $result = array("total" => $list->total(), "rows" => $list->items(),'sql'=>$this->model->getLastSql());

            return json($result);
        }
        return $this->view->fetch();
    }


    /**
     * 详情
     */
    public function detail($ids = null)
    {
        $row = $this->model
            ->alias('a')
            ->join('messages b', 'a.message_id = b.id', 'LEFT')
            ->where(['a.id' => $ids])
            ->field('a.*,b.message_type,b.title,b.content,b.fj_file')
            ->find();
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds) && !in_array($row[$this->dataLimitField], $adminIds)) {
            $this->error(__('You have no permission'));
        }
        if (false === $this->request->isPost()) {
            $this->view->assign('row', $row);
            return $this->view->fetch();
        }
        $row->is_read = 1;
        $row->read_time = time();
        $row->save();
        $count = $this->model->where(['is_read' => 0,'admin_id'=>$this->auth->id])->count();
        send_to_client($this->auth->id, '', '', 4, $count);
        $this->success();
    }

    /**
     * 一键已读
     */
    public function read($ids = null)
    {
        $this->model->whereIn('id', $ids)->where('is_read',0)->update(['is_read' => 1,'read_time' => time()]);
        
        $count = $this->model->where(['is_read' => 0,'admin_id'=>$this->auth->id])->count();
        send_to_client($this->auth->id, '', '', 4, $count);
        $this->success();
    }

    
    /**
     * 删除
     *
     * @param $ids
     * @return void
     * @throws DbException
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     */
    public function del($ids = null)
    {
        if (false === $this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ?: $this->request->post("ids");
        if (empty($ids)) {
            $this->error(__('Parameter %s can not be empty', 'ids'));
        }
        $pk = $this->model->getPk();
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            $this->model->where($this->dataLimitField, 'in', $adminIds);
        }
        $list = $this->model->where($pk, 'in', $ids)->select();

        $count = 0;
        Db::startTrans();
        try {
            foreach ($list as $item) {
                $count += $item->delete();
            }
            Db::commit();
        } catch (PDOException|Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($count) {
            $count1 = $this->model->where(['is_read' => 0,'admin_id'=>$this->auth->id])->count();
            send_to_client($this->auth->id, '', '', 4, $count1);
            $this->success();
        }
        $this->error(__('No rows were deleted'));
    }
}
