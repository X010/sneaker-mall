<?php
namespace Admin\Controller;

use Think\Controller;

class ExpressController extends BaseController
{

    private $r = array("error" => 0, "msg" => "", "data" => "");
    private $limit = 13;

    /**
     * 列表
     */
    public function express()
    {
        //读取物流公司
        $model = M('express');
        $res = $model->where("status<>9")->select();
        $this->assign("res", $res);
        $this->display('Express:express');
    }

    /**
     * 保存信息
     */
    public function save()
    {

    }

    /**
     * 物流列表详情
     */
    public function detail()
    {

    }
}