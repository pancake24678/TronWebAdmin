<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 大屏
 *
 * @icon fa fa-circle-o
 */
class Screen extends Backend
{
    protected $layout = '';
    protected $noNeedLogin = ['*'];

    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        return $this->view->fetch();
    }

    public function index1()
    {
        return $this->view->fetch();
    }

    public function index2()
    {
        return $this->view->fetch();
    }

    public function index3()
    {
        return $this->view->fetch();
    }
}
