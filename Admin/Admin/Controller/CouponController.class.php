<?php
namespace Admin\Controller;
use Think\Controller;

class CouponController extends BaseController{

    private $r = array("error"=>0,"msg"=>"","data"=>"");

    public function index(){
        $this->display('Coupon:index');
    }

    /**
     * 优惠劵列表
     */
    public function clist(){
        $this->display('Coupon:clist');
    }

}