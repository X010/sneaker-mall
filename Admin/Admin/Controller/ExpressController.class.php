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
        $id = I('id', '');
        if ($id) {
            //输要输出可编辑的数据
            $res_edit = $model->where("id=$id")->select()[0];
            $this->assign("res_edit", $res_edit);
        }

        //输出列表
        $res = $model->where("status<>9")->select();
        $this->assign("res", $res);
        $this->display('Express:express');
    }

    /**
     * 保存信息
     */
    public function save()
    {
        $express_name = I('express_name', '');
        if (!empty($express_name)) {
            $model = M('express');
            $data['express'] = $express_name;
            $data['status'] = 1;
            $res = $model->add($data);
        }
        $this->redirect(U('express/express'));
    }

    /**
     * 删除物流公司
     */
    public function delete_express()
    {

    }

    /**
     * 物流列表详情
     */
    public function detail()
    {
        $id = I('id', 0);
        if ($id) {
            $model_express = M('express');
            $model_res = $model_express->where("id=$id")->select()[0];
            $this->assign("model_res", $model_res);
        }
        $this->display('Express:express_detail');
    }

    /**
     * 保存物流详情信息
     */
    public function detail_save()
    {

    }
}