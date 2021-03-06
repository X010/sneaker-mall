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
        $id = I('id', '');
        $model = M('express');
        if (!empty($express_name)) {
            if ($id > 0) {
                //修改
                $model->where("id=$id")->setField("express", $express_name);
            } else {
                //新建
                $data['express'] = $express_name;
                $data['status'] = 1;
                $res = $model->add($data);
            }
        }
        $this->redirect(U('express/express'));
    }

    /**
     * 删除物流公司
     */
    public function delete_express()
    {
        $id = I('id', 0);
        if ($id > 0) {
            //删除他的子项
            $model_detail = M('province_express');
            $model_detail->where("express_id=$id")->setField("status", 9);

            //删除他的该物流公司
            $model_express = M('express');
            $model_express->where("id=$id")->setField("status", 9);
        }
        $this->redirect(U('express/express'));
    }

    /**
     * 删除物流公司详情
     */
    public function delete_express_detail()
    {
        $id = I('id', 0);
        $express_id = I('express_id', 0);
        if ($id > 0) {
            $model = M('province_express');
            $model->where("id=$id")->setField("status", 9);
        }
        $this->redirect(U('express/detail') . "&id=$express_id");
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


            //获取省份列表
            $model_express_province = M('province_express');
            $detail_list = $model_express_province->where("express_id=$id and status<>9")->select();
            $this->assign("model_express_province", $detail_list);
        }


        $this->display('Express:express_detail');
    }

    /**
     * 保存物流详情信息
     */
    public function detail_save()
    {
        $express_id = I('express_id', 0);
        $province = I('province', '');
        $first_price = I('first_price', 0);
        $continue_price = I('continue_price', 0);
        $id = I('id', '');
        $model = M('province_express');
        if (!empty($province) && $first_price >= 0 && $continue_price >= 0) {
            if ($id > 0) {
                //修改


            } else {
                //新建
                $data['province'] = $province;
                $data['first_price'] = $first_price;
                $data['continue_price'] = $continue_price;
                $data['express_id'] = $express_id;
                $model->add($data);
            }
        }
        $this->redirect(U('express/detail') . "&id=$express_id");
    }
}