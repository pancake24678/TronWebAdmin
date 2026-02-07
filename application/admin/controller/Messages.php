<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use Exception;
use PDOException;
use think\Db;
use think\exception\ValidateException;

/**
 * 消息主管理
 *
 * @icon fa fa-circle-o
 */
class Messages extends Backend
{

    /**
     * Messages模型对象
     * @var \app\admin\model\Messages
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Messages;
        $this->view->assign("messageTypeList", $this->model->getMessageTypeList());
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
                ->with(['admin'])
                ->where($where)
                ->order($sort, $order)
                ->paginate($limit);

            foreach ($list as $row) {
                $row->visible(['id', 'message_type', 'title', 'content', 'is_read_all', 'receive_admins', 'fj_file', 'createtime']);
                $row->visible(['admin']);
                $row->getRelation('admin')->visible(['nickname']);
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 添加
     *
     * @return string
     * @throws \think\Exception
     */
    public function add()
    {
        if (false === $this->request->isPost()) {
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);

        if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
            $params[$this->dataLimitField] = $this->auth->id;
        }
        $result = false;
        Db::startTrans();
        try {
            //是否采用模型验证
            if ($this->modelValidate) {
                $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                $this->model->validateFailException()->validate($validate);
            }
            $params['admin_id'] = $this->auth->id;
            if ($params['is_read_all'] == 1) {
                $params['receive_admins'] = '';
            } else {
                if (empty($params['receive_admin_ids'])) {
                    $this->error('请选择接收人');
                }
                $receive_admins = db('admin')->where('id', 'in', $params['receive_admin_ids'])->column('nickname');
                $params['receive_admins'] = implode(',', $receive_admins);
            }
            $result = $this->model->allowField(true)->save($params);
            // 发送系统消息
            $receive_admin_id_arr = [];
            if ($params['is_read_all'] == 1) {
                $receive_admin_id_arr = db('admin')->column('id');
            } else {
                $receive_admin_id_arr = explode(',', $params['receive_admin_ids']);
            }
            send_message($this->model->id, $receive_admin_id_arr);
            Db::commit();
        } catch (ValidateException | PDOException | Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($result === false) {
            $this->error(__('No rows were inserted'));
        }
        send_to_client(implode(',', $receive_admin_id_arr), $params['title'], $params['content']);
        $admin_msg_count = db("message_log")->where(['is_read' => 0])->group("admin_id")->field("admin_id,count('*') as cnt")->select();
        foreach ($admin_msg_count as $admin_count) {
            send_to_client($admin_count['admin_id'], '', '', 4, $admin_count['cnt']);
        }
        $this->success();
    }
}
