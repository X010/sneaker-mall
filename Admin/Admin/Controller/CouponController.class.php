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
        $model = M('coupon');
        $company = $this->get_company();
        $data['coupon_name'] = I('coupon_name', '');
        $data['coupon_money'] = I('coupon_money', '');
        $data['coupon_small_money'] = I('coupon_small_money', '');
        $data['coupon_type'] = I('coupon_type', '');
        $data['coupon_send_start'] = I('coupon_send_start', '');
        $data['coupon_send_end'] = I('coupon_send_end', '');
        $data['coupon_use_start'] = I('coupon_use_start', '');
        $data['coupon_use_end'] = I('coupon_use_end', '');
        $data['company_id'] = $company['id'];
        $data['coupon_status'] = 2;
        $id = $model->add($data);

        $this->display('Coupon:clist');
    }


    /**
     *按公司获取优惠劵列表
     */
    private function getCouponList($cid)
    {


    }
}