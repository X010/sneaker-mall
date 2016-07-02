<?php
namespace Admin\Controller;

use Think\Controller;

class CouponController extends BaseController
{

    private $r = array("error" => 0, "msg" => "", "data" => "");
    private $limit = 13;

    public function index()
    {
        $this->display('Coupon:index');
    }

    /**
     * 修改红包信息
     */
    public function edit()
    {
        $id = I('id', 0);
        $model = M('coupon');
        $data = $model->where(" id=$id")->select()[0];
        $this->assign('current_coupon', $data);
        $this->display('Coupon:edit');
    }

    /**
     * 优惠劵列表
     */
    public function clist()
    {
        $search_key = I('key', '');
        $p = I('p', 0, 'intval');
        $company = $this->get_company();

        $res = $this->getCouponList($company['id'], $p);
        $count = $this->getCouponCount($company['id']);

        $this->assign('coupon_list', $res);
        $Page = new \Extend\Page($count, $this->limit);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show = $Page->show();// 分页显示输出
        $this->assign('page', $show);
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
        $id = I('id', '');
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
        $data['merchandise'] = I('coupon_goods_ids', '');
        $data['coupon_status'] = 2;
        if (!empty($data['coupon_name']) && $data['coupon_money'] > 0) {

            if ($id > 0) {
                //修改
                $res_model_user = $model->where(" id=$id")->select()[0];

                if ($res_model_user) {
                    $res_model_user['coupon_name'] = I('coupon_name', '');
                    $res_model_user['coupon_money'] = I('coupon_money', '');
                    $res_model_user['coupon_small_money'] = I('coupon_small_money', '');
                    $res_model_user['coupon_send_start'] = I('coupon_send_start', '');
                    $res_model_user['coupon_send_end'] = I('coupon_send_end', '');
                    $res_model_user['coupon_use_start'] = I('coupon_use_start', '');
                    $res_model_user['coupon_use_end'] = I('coupon_use_end', '');
                    $res_model_user['id'] = I('id', '');
                    $res_id = $model->where("id=$id")->save($res_model_user);
                }
            } else {
                //新建
                $id = $model->add($data);
            }
        }

        $this->redirect(U('Coupon/clist'));
    }

    /**
     * 删除该红包
     */
    public function delete()
    {
        $company = $this->get_company();
        if ($company) {
            if ($company['id'] > 0) {
                //在登陆该公司的情况下才能删除
                $id = I('id', 0);
                $cid = $company['id'];
                if ($id > 0) {
                    //先清除所有发出去的红包
                    $model_detail = M('coupon_detail');
                    $res_detail = $model_detail->where("coupon_id=$id and company_id=$cid AND status<>4")->setField('status', 9); //表示删除
                    $model = M('coupon');
                    $res = $model->where("company_id=$cid AND id=$id")->setField('coupon_status', 9);
                }
            }
        }
        $this->redirect(U('Coupon/clist'));
    }

    /**
     * 优惠劵详情列表
     */
    public function detail()
    {
        $search_key = I('key', 0);
        $p = I('p', 0, 'intval');
        $company = $this->get_company();
        $model = M('coupon');
        $model_detail = M('coupon_detail');
        if (!empty($company) && $company['id'] > 0) {
            $id = I('id', 0);
            $cid = $company['id'];
            if ($id > 0) {
                $coupon = $model->where(" company_id=$cid and id=$id")->select()[0];
                //获取详情列表
                $start = 0;
                if ($p) {
                    $start = ($p - 1) * $this->limit;
                } else {
                    $start = 0;
                }

                $coupon_detail_list = $model_detail->where(" coupon_id=$id")->limit($start, $this->limit)->order(' id DESC')->select();
                $count = $model_detail->where(" coupon_id=$id")->count();

                $Page = new \Extend\Page($count, $this->limit, ['key' => $search_key, 'id' => $id]);
                $show = $Page->show();// 分页显示输出
                $this->assign('page', $show);
                $this->assign("coupon_detail_list", $coupon_detail_list);
                $this->assign("inst", $coupon);
            }
        }
        $this->display('Coupon:detail');
    }


    /**
     *按公司获取优惠劵列表
     */
    private function getCouponList($cid, $p)
    {
        $model = M('coupon');
        $where = " company_id=$cid AND coupon_status<>9 ";

        $start = 0;
        if ($p) {
            $start = ($p - 1) * $this->limit;
        } else {
            $start = 0;
        }

        $res = $model->where($where)->limit($start, $this->limit)->order('coupon_send_start DESC')->select();
        return $res;
    }

    /**
     * @param $cid 统计该公司的红包数量
     */
    private function getCouponCount($cid)
    {
        $model = M('coupon');
        return $model->where(" company_id=$cid AND coupon_status<>9")->count();
    }
}