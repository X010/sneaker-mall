<?php
namespace Admin\Controller;

use Think\Controller;

class CouponController extends BaseController
{

    private $r = array("error" => 0, "msg" => "", "data" => "");

    public function index()
    {
        $this->display('Coupon:index');
    }

    /**
     * 优惠劵列表
     */
    public function clist()
    {
        $this->display('Coupon:clist');
    }

    /**
     * 创建优惠劵
     */
    public function create()
    {
        $this->display('Coupon:create_coupon');
    }


    /**
     * 保存数据
     */
    public function save()
    {

        $this->display('Coupon:clist');
    }


    /**
     *按公司获取优惠劵列表
     */
    private function getCouponList($cid)
    {


    }
}